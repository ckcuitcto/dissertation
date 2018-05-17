<?php

namespace App\Providers;

use App\Model\EvaluationForm;
use App\Model\Permission;
use App\Model\Proof;
use App\Model\Student;
use App\Policies\EvaluationFormPolicy;
use App\Policies\PersonalInformationPolicy;
use App\Policies\ProofPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        EvaluationForm::class => EvaluationFormPolicy::class,
        Student::class => PersonalInformationPolicy::class,
        Proof::class => ProofPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function () {
            if (Auth::user()->Role->id == 6) {
                return true;
            }
        });

        if(! $this->app->runningInConsole()){
            foreach (Permission::all() as $permission){
                Gate::define($permission->name, function($user) use ($permission){
                    return $user->hasPermission($permission);
                });
            }
        }
    }
}
