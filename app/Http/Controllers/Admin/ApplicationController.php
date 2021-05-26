<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('application_Control'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.application.index');
    }
    public function ClearCache(Request $request)
    {
        abort_if(Gate::denies('application_Control'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $request->validate([
            'tags' => 'sometimes|nullable'
        ]);

        $cache_tags = $request->input('tags');
        try {
            if($cache_tags != null){
                Cache::tags($cache_tags)->flush();
                return redirect()->back()->with('message',"Cleared $cache_tags Cache!");
            }

            Artisan::call('cache:clear');
        } catch (\Throwable $th) {
            throw $th;
        }

        return redirect()->back()->with('message',"Cleared All Cache!");
    }

    public function linkStorage(Request $request)
    {
        abort_if(Gate::denies('application_Control'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            Artisan::call('storage:link');
        } catch (\Throwable $th) {
            throw $th;
        }
        return redirect()->back()->with('message',"Storage Linked!");
    }
    public function ClearAudit(Request $request)
    {
        abort_if(Gate::denies('application_Control'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            Artisan::call('truncate:audit');
        } catch (\Throwable $th) {
            throw $th;
        }
        return redirect()->back()->with('message',"Successfully truncated audit log!");
    }
    public function scraperStart(Request $request)
    {
        abort_if(Gate::denies('application_Control'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            Artisan::call('scraper:start');
        } catch (\Throwable $th) {
            throw $th;
        }
        return redirect()->back()->with('message',"Scraper Stated successfully!");
    }
    public function scraperStartCovid(Request $request)
    {
        abort_if(Gate::denies('application_Control'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            Artisan::call('scraper:covid');
        } catch (\Throwable $th) {
            throw $th;
        }
        return redirect()->back()->with('message',"Covid Scraper Stated successfully");
    }
    public function cleanMedia(Request $request)
    {
        abort_if(Gate::denies('application_Control'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            Artisan::call('media-library:clean');
        } catch (\Throwable $th) {
            throw $th;
        }
        return redirect()->back()->with('message',"Orphaned images cleaned!");
    }
    public function covidCommands(Request $request)
    {
        abort_if(Gate::denies('application_Control'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'command' => 'required'
        ]);

        $command = $request->input('command');
        try {
            if($command == 'historical'){
                Artisan::call('covid:historical');
            } else if($command == 'worldometers'){
                Artisan::call('covid:worldometers');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        return redirect()->back()->with('message',"$command scraper command Stated successfully");
    }

    public function clearTokens(Request $request)
    {
        abort_if(Gate::denies('application_Control'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            Artisan::call('auth:clear-resets');
        } catch (\Throwable $th) {
            throw $th;
        }

        return redirect()->back()->with('message',"Cleared expired tokens");
    }

    public function clearAllCache(Request $request)
    {
        abort_if(Gate::denies('application_Control'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            Artisan::call('cache:all-clear');
        } catch (\Throwable $th) {
            throw $th;
        }

        return redirect()->back()->with('message',"All Application Cache successfully removed");
    }

    public function startMaintainance(Request $request)
    {
        abort_if(Gate::denies('application_Control'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            Artisan::call('down');
        } catch (\Throwable $th) {
            throw $th;
        }

        return redirect()->back()->with('error',"Website Down!!");
    }

    public function stopMaintainance(Request $request)
    {
        abort_if(Gate::denies('application_Control'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            Artisan::call('up');
        } catch (\Throwable $th) {
            throw $th;
        }

        return redirect()->back()->with('message',"Website up again!!");
    }
}
