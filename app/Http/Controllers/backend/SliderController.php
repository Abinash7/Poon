<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Slide;
use App\Model\Career;
use Illuminate\Http\Request;
use File;

class SliderController extends backendController
{
    // function __construct()
    // {
    //     $this->middleware('auth');
        
    // }
    public function slider(Request $request)
    {
        $slider= Slide::all();
        $this->data('slides',$slider);
        $this->data('slider', $this->title( 'slider'));
        return view($this->pagePath . 'slider.slider');
    }


    public function delete_Career_view($id)
    {
        $find = Career::findorfail($id);
        $this->delete_file($id);

        if ($find->delete()) {
            return redirect()->back()->with('success', ' Deleted');
        }
    }

    // public function delete_file($id)
    // {
    //     $findData = Slide::findorfail($id);
    //     $fileName = $findData->image;
    //     $deletePath = public_path('Images/' . $fileName);
    //     if (file_exists($deletePath) && is_file($deletePath)) {
    //         unlink($deletePath);
    //     }
    //     return true;
    // }

    public function delete_show($id)
    {
        $find = Slide::findorfail($id);
        $this->delete_file($id);

        if ($find->delete()) {
            return redirect()->back()->with('success', 'Show Deleted');
        }
    }

    public function delete_file($id)
    {
        $findData = Slide::findorfail($id);
        $fileName = $findData->image;
        $deletePath = public_path('Images/' . $fileName);
        if (file_exists($deletePath) && is_file($deletePath)) {
            unlink($deletePath);
        }
        return true;
    }

    public function Career(){
        $careers = Career::all();
                
        return view($this->pagePath. 'Career.Career_view')->with('careers',$careers);
    }
    public function show(){
        $slides = Slide::all();
                
        return view($this->pagePath. 'slider.show')->with('slides',$slides);
    }
    public function add_slider(Request $request)
    {
        if ($request->isMethod('get')) {
            return view($this->pagePath . 'slider.add_slider');
        }
        if ($request->isMethod('post')) {
            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'image' => 'max:20480'
            ]);
            $data= $request->all();
            $slide=new Slide();

            if ($request->image){
                $path = public_path().'/images';
                if(!File::exists($path)){
                    File::makeDirectory($path,0777,true,true);
                }
                $file_name="image-".time().".".$request->image->getClientOriginalExtension();
                $request->image->move($path,$file_name);
                $data['image']=$file_name;
            $slide->fill($data);
            $slide->save();

            return redirect()->back();
            }
        }
    }


    public function edit_slider(Request $request)
    {
        if ($request->isMethod('get')) {
            $finddata = Slide::where('id', '=', $request->id)->first();
            $this->data('slide', $finddata);
            $this->data('title', $this->title('Edit Demands'));
            return view($this->pagePath . 'slider.edit_slider');
        }
        if ($request->isMethod('post')) {
            $id = $request->id;
            $request->validate([
            'title' => 'required',
//                'date' => 'required',
                'description' => 'required'
            ]);
            if ($request->hasFile('image')) {
              
                $image = $request->file('image');
                $file_name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/images/');
                $image->move($destinationPath, $file_name);
                $data['image'] = $file_name;
            }
             $data['title'] = $request->title;
            // if ($request->date) {
            //     $data['date'] = $request->date;

            // }

            $data['description'] = $request->description;
            $create = Slide::findorfail($id);
            if ($create->update($data)) {
                return redirect()->back()->with('success', 'Details Updated');
            }
        }
    }
}
