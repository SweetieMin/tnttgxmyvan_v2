<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $loginId = $this->input('login_id');
        $fieldType = filter_var($loginId, FILTER_VALIDATE_EMAIL) ? 'email' : 'account_code';
        
        if ($fieldType === 'email') {
            return [
                'login_id' => ['required', 'email', 'exists:users,email'],
                'password' => ['required', 'string', 'min:5', 'max:45'],
            ];
        }

        return [
            'login_id' => ['required', 'string', 'exists:users,account_code'],
            'password' => ['required', 'string', 'min:5', 'max:45'],
        ];
    }

    public function messages(): array
    {
        return [
            'login_id.required' => 'Mã tài khoản/Email là bắt buộc',
            'login_id.email' => 'Địa chỉ email không hợp lệ',
            'login_id.exists' => 'Thông tin đăng nhập không hợp lệ',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password.min' => 'Mật khẩu phải có trên 5 ký tự',
            'password.max' => 'Mật khẩu tối đa 45 ký tự',
        ];
    }

    /**
     * Validate the request's credentials and return the user without logging them in.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateCredentials(): User
    {
        $this->ensureIsNotRateLimited();

        $loginId = $this->input('login_id');
        $password = $this->input('password');
        $remember = $this->boolean('remember');
        $fieldType = filter_var($loginId, FILTER_VALIDATE_EMAIL) ? 'email' : 'account_code';

        // Chuẩn bị credentials cho Auth::attempt
        $credentials = [
            $fieldType => $loginId,
            'password' => $password
        ];

        // Sử dụng Auth::attempt để xác thực
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Kiểm tra trạng thái tài khoản
            if ($user->status_login === 'inactive') {
                Auth::logout();
                throw ValidationException::withMessages([
                    'login_id' => __('Tài khoản của bạn hiện đang bị khóa. Vui lòng liên hệ trang Thiếu Nhi để được xử lý.'),
                ]);
            }

            if ($user->status_login === 'lock') {
                Auth::logout();
                throw ValidationException::withMessages([
                    'login_id' => __('Tài khoản của bạn hiện đang bị khóa. Vui lòng liên hệ trang Thiếu Nhi để được xử lý.'),
                ]);
            }

            // Nếu login bằng email và chưa xác minh
            if ($fieldType === 'email' && $user->email_verified_at === null) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'login_id' => __('Email này chưa được xác minh để đăng nhập. Vui lòng sử dụng Mã tài khoản.'),
                ]);
            }

            RateLimiter::clear($this->throttleKey());
            return $user;
        }

        // Nếu đăng nhập thất bại
        RateLimiter::hit($this->throttleKey());
        throw ValidationException::withMessages([
            'login_id' => __('Thông tin đăng nhập không hợp lệ'),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login_id' => "Bạn đã đăng nhập sai quá nhiều lần.",
        ]);
    }

    /**
     * Get the rate-limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return $this->string('login_id')
            ->lower()
            ->append('|'.$this->ip())
            ->transliterate()
            ->value();
    }
}
