<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Robots\StoreRobot;
use App\Http\Requests\Robots\updateRobot;
use App\Models\Robot;
use App\Services\ProxyChecker;

class Robots extends Controller
{

    
    public function showAll()
    {
        $robots = Robot::paginate(10);
        
        $proxies = array_column(Robot::select('proxy')->get()->toArray() , 'proxy');

        $checkProxies = new ProxyChecker();

        $checkProxies->CheckMultiProxy($proxies , new Robot());

        return view('admin.list-robots' , ['robots' => $robots]);
    }


    public function add()
    {
        return view('admin.add-robots');
    }


    public function store(StoreRobot $request)
    {
        $postedData = $request->validated();

        try {
            $robot = Robot::firstOrCreate(['username' => $postedData['username']],[
                'username' => $postedData['username'],
                'password' => $postedData['password'],
                'proxy' => $postedData['proxy']
            ]);

        } catch (\Exception $e) {
            return back()->with('failed' , $e->getMessage());
        }

        return redirect()->route('robots.list')->with('success' , 'روبات جدید با نام ' . $robot->username . ' ایجاد شد ');
    }


    public function edit(int $robot_id)
    {
        $robot = Robot::find($robot_id);

        return view('admin.up-robots' , ['robot' => $robot]);
    }


    public function update(updateRobot $request , $robot_id)
    {
        $postedData = $request->validated();

        try {
            
            $robot = Robot::find($robot_id);

            $robot->update([
                'username' => $postedData['username'],
                'password' => $postedData['password'],
                'proxy' => $postedData['proxy']
            ]);

        } catch (\Exception $e) {
            return back()->with('failed' , $e->getMessage());
        }

        return redirect()->route('robots.list')->with('success' , 'روبات بروزرسانی شد');
    }


    public function delete(int $robot_id)
    {
        try {
            Robot::find($robot_id)->delete();
        } catch (\Exception $e) {
            return back()->with('failed' , $e->getMessage());
        }

        return back()->with('success' , 'روبات حذف شد');
    }


    public function changeProxy(int $robot_id)
    {
        try {
            ProxyChecker::changProxiesForRobot([$robot_id]);
 
        } catch (\Exception $e) {
            return back()->with('failed' , $e->getMessage());
        }

        return back()->with('success' , 'پروکسی روبات تغییر کرد');

    }


}
