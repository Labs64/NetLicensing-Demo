<?php

namespace App\Http\Controllers;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Http\Request;

class TryAndBuyController extends Controller
{
    protected $setup;

    public function __construct()
    {
        $this->setup = collect($this->generate());
    }

    public function index(Request $request)
    {
        return view('try_and_buy')->with('setup', $this->setup);
    }

    public function regenerate(Request $request)
    {
        $generated = [
            'username' => config('nlic.auth.username'),
            'password' => config('nlic.auth.password'),
        ];

        if ($request->expectsJson()) {
            return response()->json($generated);
        }

        return redirect()->back()->with('generated', $generated);
    }

    protected function generate()
    {
        $auth = \Cache::get('nlic.auth', function () {
            return [
                'username' => config('nlic.auth.username'),
                'password' => config('nlic.auth.password'),
            ];
        });

        $data = \Cache::get('nlic.try_and_buy', function () {
            $faker = Factory::create();

            return [
                'product' => [
                    'number' => $faker->uuid
                ]
            ];
        });

        \Cache::add('nlic.auth', $auth, config('nlic.lifetime'));
        \Cache::add('nlic.try_and_buy', $data, config('nlic.lifetime'));

        return $auth + $data;
    }
}
