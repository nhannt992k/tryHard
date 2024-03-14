<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Response;

use function PHPUnit\Framework\isNull;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Address::query()
                ->select('address')
                ->where('user_id', auth()->user()->id)
                ->get();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required',
            'user_id' => 'required',
            'is_default' => 'required',
        ]);

        try {
            if ($validated) {
                $data = Address::create($request->only('address', 'user_id', 'is_default'));
                return response()->json(['message' => 'Địa chỉ của bạn đã được thêm thành công'], Response::HTTP_CREATED);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        try {
            $data = Address::find($address->id);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Address $address)
    {
        $validated = $request->validate([
            'address' => 'required',
            'is_default' => 'required',
        ]);
        try {
            if ($validated) {
                $fields = $request->only("address", "is_default");
                $fields = array_filter($fields, fn ($value) =>!isNull($value));
                $data = Address::where('id', $address->id)->update($fields);
                return response()->json(['message' => 'Chỉnh sửa địa chỉ thành công'], Response::HTTP_ACCEPTED);
            
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Address $address)
    {
        try {
            $data = Address::where('id', $address->id)
            ->where('user_id', $user->id)
            ->delete();
            return response()->json(['message'=> 'Xoá địa chỉ thành công'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error'=> $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
