<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Symfony\Component\HttpFoundation\Response;
use Exception;
use Illuminate\Support\Facades\Hash;

use App\Jobs\SendRegistrationConfirmation;
use PhpParser\Node\Stmt\TryCatch;

use function PHPUnit\Framework\isNull;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {  /* dd($request); */
        // Tạo rule cho kết quả nhập vào đây là demo nên làm
        $rules = [
            'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'email' => 'nullable|email',
            'username' => 'nullable|string|max:255',
            'password' => 'required',
        ];

        $messages = [
            'phone.regex' => 'Số điện thoại không hợp lệ.',
            'phone.min' => 'Số điện thoại phải có ít nhất 10 ký tự.',
            'email.email' => 'Email không hợp lệ.',
            'username.string' => 'Tên người dùng phải là một chuỗi ký tự.',
            'username.max' => 'Tên người dùng không được vượt quá 255 ký tự.',
        ];
        /*  $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]); */
        $validated = $this->validate($request, $rules, $messages);
        /* dd($validated); */

        try {
            if ($validated) {
                $request['password'] = Hash::make($request['password']);
                $request['role_id'] = $request['role_id'] ?? 0;
                $user = User::create($request->all());
                //Đến dây sẽ cần phải gửi email xác thực! Hiện tại đang test nên chưa cần
                return response()->json(['message' => 'Tạo tài khoản thành công'], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Vui lòng điền đày đủ thông tin'], Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Lỗi chưa xác định, vui lòng thử lại sau vài phút'], Response::HTTP_BAD_GATEWAY);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            $data = User::select('username', 'phone_number', 'email', 'email_verified_at', 'avatar')->find($user->id);
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            "name" => "required",
            'avatar.*' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        try {
            if ($validated) {
                $fields = $request->only("name", "avatar");
                $fields = array_filter($fields, fn ($value) => !isNull($value));
                //Chưa lưu hình ảnh cần phải kiểm tra kỹ hơn 
                $data = User::where("id", $user->id)->update($fields);
                return response()->json(['message' => 'Cập nhật thành công'], Response::HTTP_OK);
            } else {
                return response()->json(['error'=> 'Vui lòng nhập đúng định dạng'], Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return response()->json(['error'=> 'Lỗi không xác định'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $isdelte = User::where('id', $user->id)->delete();
            if ($isdelte) {
                //Sau này sẽ thành xoá tài khoản sau 30 ngày
            return response()->json(['message'=> 'Xoá tài khoản thành công'], Response::HTTP_OK);
            }else {
                return response()->json(['error'=> 'Xoá tài khoản không thành công'], Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return response()->json(['error'=> 'Lỗi không xác định'], Response::HTTP_BAD_REQUEST);
            
        }
    }
}
