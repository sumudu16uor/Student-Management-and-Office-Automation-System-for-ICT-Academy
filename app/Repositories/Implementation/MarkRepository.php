<?php

namespace App\Repositories\Implementation;

use App\Http\Requests\StoreMarkRequest;
use App\Http\Requests\UpdateMarkRequest;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\Student;
use App\Repositories\Interfaces\MarkRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

class MarkRepository implements MarkRepositoryInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllStudentsMarks(Request $request)
    {
        if ($request->date != null) {
            return Student::query()
                ->with(['exams' => function ($query) use ($request) {
                    $query->whereYear('date', data_get($request, 'date'));
                }, 'person'])
                ->whereHas('exams', function (Builder $query) use ($request) {
                    $query->whereYear('date', data_get($request, 'date'));
                })->get();
        }

        return Student::query()
            ->with(['exams' => function ($query) {
                $query->whereYear('date', Carbon::now()->year);
            }, 'person'])
            ->whereHas('exams', function (Builder $query) {
                $query->whereYear('date', Carbon::now()->year);
            })->get();
    }

    /**
     * @param StoreMarkRequest $request
     * @return mixed
     */
    public function attachStudentMark(StoreMarkRequest $request)
    {
        $student = Student::query()->find(data_get($request, 'studentID'));

        $student->exams()->attach(data_get($request, 'examID'));

        return $student;
    }

    /**
     * @param Student $student
     * @return mixed
     */
    public function getStudentMarkById(Student $student)
    {
        return Student::query()->with(['exams', 'person'])->find($student->studentID);
    }

    /**
     * @param Request $request
     * @param Student $student
     * @return mixed
     */
    public function getStudentMarkByYear(Request $request, Student $student)
    {
        if ($request->date != null) {
            return Student::query()
                ->with(['exams' => function ($query) use ($request) {
                    $query->whereYear('date', data_get($request, 'date'));
                }, 'person'])
                ->find($student->studentID);
        }

        return Student::query()
            ->with(['exams' => function ($query) {
                $query->whereYear('date', Carbon::now()->year);
            }, 'person'])
            ->find($student->studentID);
    }

    /**
     * @param Student $student
     * @param Exam $exam
     * @return mixed
     */
    public function getExamMarkByStudentId(Student $student, Exam $exam)
    {
        return Student::query()
            ->with(['exams' => function ($query) use ($exam) {
                $query->where('exams.examID', $exam->examID);
            }, 'person'])
            ->find($student->studentID);
    }

    /**
     * @param UpdateMarkRequest $request
     * @param Student $student
     * @param Exam $exam
     * @return mixed
     */
    public function updateStudentMark(UpdateMarkRequest $request, Student $student, Exam $exam)
    {
        $student = Student::query()->find($student->studentID);

        $student->exams()->updateExistingPivot($exam, [
           'mark' => $request->mark != null ? data_get($request, 'mark') : 'Ab'
        ]);

        return $student;
    }

    /**
     * @param Student $student
     * @param Exam $exam
     * @return mixed
     * @throws Exception
     */
    public function detachStudentMark(Student $student, Exam $exam)
    {
        $deleted = $student->exams()->detach($exam);

        if (!$deleted){
            throw new Exception('Failed to detach ' . $exam->exam . ' exam of: ' . $student->studentID);
        }

        return $deleted;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllExamsMarks(Request $request)
    {
        if ($request->date != null) {
            return Exam::query()->has('students')
                ->with(['students', 'students.person'])
                ->whereYear('date', data_get($request, 'date'))
                ->get();
        }

        return Exam::query()->has('students')
            ->with(['students', 'students.person'])
            ->whereYear('date', Carbon::now()->year)
            ->get();
    }

    /**
     * @param StoreMarkRequest $request
     * @return mixed
     * @throws Throwable
     */
    public function attachExamMarks(StoreMarkRequest $request)
    {
        $exam = Exam::query()->find(data_get($request, 'examID'));

        $students = Enrollment::query()
            ->where('classID', $exam->classID)
            ->where('status', '1')
            ->pluck("studentID");

        DB::transaction(function () use ($exam, $students) {

            $exam->students()->attach($students);
        });

        return $students;
    }

    /**
     * @param Exam $exam
     * @return mixed
     */
    public function getExamMarkById(Exam $exam)
    {
        return Exam::query()->with(['students', 'students.person'])->find($exam->examID);
    }

    /**
     * @param Exam $exam
     * @return mixed
     */
    public function getExamMarkAbsent(Exam $exam)
    {
        return Exam::query()
            ->with(['students' => function ($query) {
                $query->where('mark', 'Ab');
            }, 'students.person'])
            ->find($exam->examID);
    }

    /**
     * @param Request $request
     * @param Exam $exam
     * @return mixed
     */
    public function getExamMarksAbove(Request $request, Exam $exam)
    {
        return Exam::query()
            ->with(['students' => function ($query) use ($request) {
                $query->where('mark', '>=', data_get($request, 'mark'));
            }, 'students.person'])
            ->find($exam->examID);
    }

    /**
     * @param Exam $exam
     * @return mixed
     */
    public function getExamMarkAttendCount(Exam $exam)
    {
       return Mark::query()
           ->where('examID', $exam->examID)
           ->get();
    }

    /**
     * @param UpdateMarkRequest $request
     * @param Exam $exam
     * @return mixed
     */
    public function updateExamMarks(UpdateMarkRequest $request, Exam $exam)
    {
        return $exam->students()
            ->where('mark.examID', $exam->examID)
            ->update([
                'mark' => data_get($request, 'mark')
            ]);
    }

    /**
     * @param Exam $exam
     * @return mixed
     * @throws Exception
     */
    public function detachExamMark(Exam $exam)
    {
        $deleted = $exam->students()->detach();

        if (!$deleted){
            throw new Exception('Failed to detach marks of: ' . $exam->examID);
        }

        return $deleted;
    }
}
