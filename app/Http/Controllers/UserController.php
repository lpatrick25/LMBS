<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $count = 1;
        $response = [];

        // Query for borrowers
        $borrowerQuery = DB::table('users')
            ->join('user_profiles', 'user_profiles.user_id', '=', 'users.user_id')
            ->select(
                'user_profiles.first_name',
                'user_profiles.middle_name',
                'user_profiles.last_name',
                'user_profiles.extension_name',
                'users.user_role',
                'users.user_id',
                'users.username',
            );

        $borrowers = $borrowerQuery->get();

        // Process borrowers
        foreach ($borrowers as $user) {

            $fullName = $this->formatFullName(
                $user->first_name,
                $user->middle_name,
                $user->last_name,
                $user->extension_name
            );

           


            $response[] = [
                'count' => $count++,
                'fullname' => $fullName,
                'username' => $user->username,
                'user_role' => $user->user_role,
                'action' => '<button title="Edit Password" class="btn btn-primary btn-md" onclick="pass(\'' . $user->user_id . '\')"><i class="fa fa-edit"></i></button>',
            ];
        }

        return response()->json(['data' => $response]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user_id)
    {
        try {
            DB::beginTransaction();

            $user = User::where('user_id', $user_id)->first();

            if (!$user) {
                throw new \Exception("User not found");
            }

            $user->update([
                'password' => Hash::make($request->password),
            ]);

            DB::commit();
            return response()->json(['valid' => true, 'msg' => 'Password change successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['valid' => false, 'msg' => 'Failed to register: ' . $e->getMessage()], 422);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = User::where('user_id', session()->get('user_id'))->first();

            if (!$user) {
                throw new \Exception("User not found");
            }

            $user->update([
                'password' => Hash::make($request->password),
            ]);

            DB::commit();
            return response()->json(['valid' => true, 'msg' => 'Password change successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['valid' => false, 'msg' => 'Failed to change password: ' . $e->getMessage()], 422);
        }
    }

    public function checkUsername(Request $request)
    {
        $username = $request->input('username');
        $exists = DB::table('users')->where('username', $username)->exists();

        return response()->json(!$exists); // Only returns true or false
    }

    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        $exists = DB::table('user_profiles')->where('email', $email)->exists();

        return response()->json(!$exists); // Only returns true or false
    }

    public function checkContact(Request $request)
    {
        $contact = $request->input('contact_no');
        $exists = DB::table('user_profiles')->where('contact_no', $contact)->exists();

        return response()->json(!$exists); // Only returns true or false
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Optional: Flush the session data
        $request->session()->flush();

        // Optional: Regenerate the session ID
        // $request->session()->regenerate();

        return response()->json(['valid' => true, 'msg' => 'Logout Success.'], 200);
    }

    public function login(Request $request)
    {
        try {
            $username = $request->username;
            $password = $request->password;

            if (Auth::attempt(['username' => $username, 'password' => $password])) {
                $user = auth()->user();
                $user_id = $user->user_id;
                $user_role = $user->user_role;
                $username = $user->username;

                // Set base session data
                session([
                    'user_id' => $user->user_id,
                    'user_role' => $user_role,
                    'username' => $username,
                ]);

                if ($user_role === "Admin") {
                    $fullName = "Administrator";
                } else {
                    $profile = DB::table('user_profiles')->where('user_id', $user_id)->first();
                    // $profileImage = $profile->image;
                    $fullName = $this->formatFullName(
                        $profile->first_name,
                        $profile->middle_name,
                        $profile->last_name,
                        $profile->extension_name
                    );
                }

                // Set session values for profile image and full name
                session([
                    'fullname' => $fullName,
                ]);

                return response()->json(['valid' => true, 'msg' => 'Login Success.'], 200);
            }

            return response()->json(['valid' => false, 'msg' => 'Invalid credentials.'], 200);
        } catch (\Exception $e) {
            Log::error('Failed to login: ' . $e->getMessage());
            return response()->json(['valid' => false, 'msg' => 'Failed to login', 'error' => $e->getMessage()], 500);
        }
    }

    public function myProfile()
    {
        try {
            $userProfile = DB::table('user_profiles')->where('user_id', session('user_id'))->first();

            if (!$userProfile) {
                return response()->json(['valid' => false, 'msg' => 'User not found'], 200);
            }

            $fullName = $this->formatFullName(
                $userProfile->first_name,
                $userProfile->middle_name,
                $userProfile->last_name,
                $userProfile->extension_name
            );

            $formattedContactNo = $this->formatContactNumber($userProfile->contact_no);

            return response()->json([
                'valid' => true,
                'msg' => 'User profile successfully retrieve',
                'fullname' => $fullName,
                'first_name' => $userProfile->first_name,
                'middle_name' => $userProfile->middle_name,
                'last_name' => $userProfile->last_name,
                'extension_name' => $userProfile->extension_name,
                'contact_no' => $formattedContactNo,
                'email' => $userProfile->email,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve profile: ' . $e->getMessage());
            return response()->json(['valid' => false, 'msg' => 'Failed to retrieve profile', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Format the full name with proper handling of the middle name.
     *
     * @param string $firstName
     * @param string|null $middleName
     * @param string $lastName
     * @param string|null $extensionName
     * @return string
     */
    private function formatFullName($firstName, $middleName, $lastName, $extensionName)
    {
        $middleInitial = $middleName ? strtoupper(substr($middleName, 0, 1)) . '.' : '';
        $fullName = trim("{$firstName} {$middleInitial} {$lastName} {$extensionName}");
        return $fullName;
    }

    /**
     * Formats a contact number from (+63) 905-747-3104 to 09057473104
     *
     * @param string $contactNo
     * @return string
     */
    private function formatContactNumber($contactNo)
    {
        // Remove spaces, parentheses, and dashes
        $cleaned = preg_replace('/[^\d]/', '', $contactNo);

        // Replace country code +63 with 0
        if (str_starts_with($cleaned, '63')) {
            $cleaned = '0' . substr($cleaned, 2);
        }

        return $cleaned;
    }
}
