<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // pass most popular subforums + total score count info to sidebar
        View::composer('layouts.aside', function ($view) {
            $subforums = DB::table('subforums')
                ->select('subforums.id', 'subforums.subforumName', DB::raw('SUM(posts.scoreCount) as totalScoreCount'))
                ->leftJoin('posts', 'subforums.id', '=', 'posts.subforumID')
                ->groupBy('subforums.id', 'subforums.subforumName')
                ->orderBy('totalScoreCount', 'desc')
                ->get();

            $view->with('subforums', $subforums);
        });

        //pass loggedUser
        View::composer('layouts.nav', function ($view) {
            if (Auth::user()) {

                $loggedUser = Auth::user()->name;
                $view->with('loggedUser', $loggedUser);
            }
        });

        View::composer('partials.post-card', function ($view) {
            if (Auth::user()) {
                $authUserId = Auth::user()->id;

                $view->with('id', $authUserId);
            }
        });

        View::composer('layouts.nav', function ($view) {
            if (Auth::user()) {

                $authUserRating = Auth::user()->rating;

                $view->with('userRating', $authUserRating);
            }
        });

    }
}
