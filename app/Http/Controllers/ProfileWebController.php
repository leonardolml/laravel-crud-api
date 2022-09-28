<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;


class ProfileWebController extends Controller
{
    public function index()
    {
        // $profile = Profile::create([
        //     'name' => 'Kid grandson',
        //     'parent_id' => 4
        // ]);
        $profile = Profile::find(3);
        
        dd($profile, $this->descendants($profile));
    }

    public $descendants;

    public function __construct()
    {
        $this->descendants = collect([]);
    }

    public function descendants($profile)
    {
        foreach ($profile->children as $children) {
            $this->descendants->push($children);
            if ($children->children->isNotEmpty()) {
                $this->descendants($children);
            }            
        }
        
        return $this->descendants;
    }
}
