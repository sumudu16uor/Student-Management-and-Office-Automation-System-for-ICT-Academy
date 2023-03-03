<?php

namespace App\Providers;

use App\Repositories\Implementation\AdvanceRepository;
use App\Repositories\Implementation\AttendanceRepository;
use App\Repositories\Implementation\BranchRepository;
use App\Repositories\Implementation\CategoryRepository;
use App\Repositories\Implementation\ClassesRepository;
use App\Repositories\Implementation\EmployeeRepository;
use App\Repositories\Implementation\EnrollmentRepository;
use App\Repositories\Implementation\ExamRepository;
use App\Repositories\Implementation\ExpenditureRepository;
use App\Repositories\Implementation\FeeRepository;
use App\Repositories\Implementation\MarkRepository;
use App\Repositories\Implementation\ParentsRepository;
use App\Repositories\Implementation\PersonRepository;
use App\Repositories\Implementation\ProcessRepository;
use App\Repositories\Implementation\StaffRepository;
use App\Repositories\Implementation\StudentRepository;
use App\Repositories\Implementation\SubjectRepository;
use App\Repositories\Implementation\TeacherRepository;
use App\Repositories\Implementation\UserRepository;
use App\Repositories\Interfaces\AdvanceRepositoryInterface;
use App\Repositories\Interfaces\AttendanceRepositoryInterface;
use App\Repositories\Interfaces\BranchRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ClassesRepositoryInterface;
use App\Repositories\Interfaces\EmployeeRepositoryInterface;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use App\Repositories\Interfaces\ExamRepositoryInterface;
use App\Repositories\Interfaces\ExpenditureRepositoryInterface;
use App\Repositories\Interfaces\FeeRepositoryInterface;
use App\Repositories\Interfaces\MarkRepositoryInterface;
use App\Repositories\Interfaces\ParentsRepositoryInterface;
use App\Repositories\Interfaces\PersonRepositoryInterface;
use App\Repositories\Interfaces\ProcessRepositoryInterface;
use App\Repositories\Interfaces\StaffRepositoryInterface;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Repositories\Interfaces\SubjectRepositoryInterface;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AdvanceRepositoryInterface::class, AdvanceRepository::class);
        $this->app->bind(AttendanceRepositoryInterface::class, AttendanceRepository::class);
        $this->app->bind(BranchRepositoryInterface::class, BranchRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ClassesRepositoryInterface::class, ClassesRepository::class);
        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);
        $this->app->bind(EnrollmentRepositoryInterface::class, EnrollmentRepository::class);
        $this->app->bind(ExamRepositoryInterface::class, ExamRepository::class);
        $this->app->bind(ExpenditureRepositoryInterface::class, ExpenditureRepository::class);
        $this->app->bind(FeeRepositoryInterface::class, FeeRepository::class);
        $this->app->bind(MarkRepositoryInterface::class, MarkRepository::class);
        $this->app->bind(ParentsRepositoryInterface::class, ParentsRepository::class);
        $this->app->bind(PersonRepositoryInterface::class, PersonRepository::class);
        $this->app->bind(ProcessRepositoryInterface::class, ProcessRepository::class);
        $this->app->bind(StaffRepositoryInterface::class, StaffRepository::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(SubjectRepositoryInterface::class, SubjectRepository::class);
        $this->app->bind(TeacherRepositoryInterface::class, TeacherRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
