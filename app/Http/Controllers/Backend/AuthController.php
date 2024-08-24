<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function checkAuth(): JsonResponse
    {
        try {
            $user = '';
            if (auth()->check()) {
                $user = User::with('roles:user_id,name')->where('id', auth()->user()->id)->first();
                return sendSuccessResponse('User Authenticate', 200, $user);
            } else {
                return sendErrorResponse('User Unauthenticated', 404);
            }
        } catch (Exception $exception) {
            return sendErrorResponse('Something went wrong : ' . $exception->getMessage(), 404);
        }
    }

    public function login(RequestAuth $request): JsonResponse
    {
        try {
            $user = User::where('email', $request->email)->first();
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $data = [
                    'user' => $user->load('roles:user_id,name'),
                ];
            } else {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
        } catch (Exception $exception) {
            return sendErrorResponse('Something went wrong: ' . $exception->getMessage());
        }
        return sendSuccessResponse('Logged in Successfully!!', '200', $data);
    }


    public function registerStep1(RequestRegister $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $userType = $request->userType === 'trial' ? UserType::USER_TYPE_TRIAL : UserType::USER_TYPE_PAID;

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt('12345678')
            ]);
            $user->roles()->create([
                'name' => UserRole::ROLE_USER,
            ]);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            return sendErrorResponse('Something went wrong : ' . $exception->getMessage());
        }
        return sendSuccessResponse('User created successfully', 200, $user);
    }


    public function registerStepLast(RequestCompany $request): JsonResponse
    {
        try {
            $userExist = User::whereId($request->userId)->first();
            if ($userExist) {
                DB::beginTransaction();
                CompanyInformation::create([
                    'user_id' => $request->userId,
                    'name' => $request->companyInformation['name'],
                    'job_title' => $request->companyInformation['job_title'],
                    'industry_type' => $request->companyInformation['industry_type'],
                    'address' => $request->companyInformation['address'],
                    'postal_code' => $request->companyInformation['postal_code'],
                    'city' => $request->companyInformation['city'],
                    'country' => $request->companyInformation['country'],
                    'company_email' => $request->companyInformation['company_email'],
                    'company_phone' => $request->companyInformation['company_phone']
                ]);

                $userExist->update([
                   'is_profile_completed' => true
                ]);
                DB::commit();
            }
        } catch (Exception $exception) {
            DB::rollBack();
            return sendErrorResponse('Something Went wrong : ' . $exception->getMessage());
        }
        return sendSuccessResponse('User Registration Successful!!', 200, $userExist);
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            auth()->guard('web')->logout();
        } catch (Exception $exception) {
            return sendErrorResponse('Something went wrong: ' . $exception->getMessage());
        }
        return sendSuccessResponse('Logout successful');
    }

}
