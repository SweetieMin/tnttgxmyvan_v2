<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Features;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Login extends Component
{
    public string $login_id = '';

    public string $password = '';

    public bool $remember = false;

    public string $fieldType =  'email';

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->resetErrorBag();
        $this->fieldType = filter_var($this->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'account_code';


        $this->validate($this->rules($this->fieldType), $this->messages());

        $this->ensureIsNotRateLimited();

        $user = $this->validateCredentials();

        $this->checkStatusLogin($user);

        if (Features::canManageTwoFactorAuthentication() && $user->hasEnabledTwoFactorAuthentication()) {
            Session::put([
                'login.id' => $user->getKey(),
                'login.remember' => $this->remember,
            ]);


            $this->redirect(route('two-factor.login'), navigate: true);


            return;
        }

        Auth::login($user, $this->remember);

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Kiểm tra trạng thái tài khoản trước khi đăng nhập
     */
    protected function checkStatusLogin(User $user): void
    {
        // Nếu cột status_login khác "active", chặn đăng nhập
        if ($user->status_login !== 'active') {

            // Ghi log hoặc trigger event nếu cần
            RateLimiter::hit($this->throttleKey());

            // Tùy thông điệp hiển thị, có thể dựa vào giá trị cụ thể
            $message = match ($user->status_login) {
                'inactive' => 'Tài khoản của bạn đã hết hạn. Vui lòng liên hệ quản trị viên nếu bạn cần đăng nhập.',
                'locked' => 'Tài khoản của bạn đang bị khóa. Vui lòng thử lại sau hoặc liên hệ quản trị viên.',
                default => 'Tài khoản của bạn không hợp lệ để đăng nhập.',
            };

            throw ValidationException::withMessages([
                'login_id' => $message,
            ]);
        }
    }

    /**
     * Validate the user's credentials.
     */
    protected function validateCredentials(): User
    {
        // Lấy user theo email hoặc mã tài khoản
        $user = Auth::getProvider()->retrieveByCredentials([
            $this->fieldType => $this->login_id,
            'password' => $this->password,
        ]);

        // Nếu user không tồn tại
        if (! $user) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'login_id' => 'Thông tin đăng nhập không chính xác.',
            ]);
        }

        // Nếu sai mật khẩu
        if (! Auth::getProvider()->validateCredentials($user, ['password' => $this->password])) {
            RateLimiter::hit($this->throttleKey());

            // ✅ Đếm số lần đăng nhập sai
            $attempts = RateLimiter::attempts($this->throttleKey());

            if ($attempts >= 5) {
                // ✅ Khóa tài khoản
                $user->update(['status_login' => 'locked']);

                throw ValidationException::withMessages([
                    'login_id' => 'Tài khoản của bạn đã bị khóa do nhập sai mật khẩu quá nhiều lần. Vui lòng liên hệ quản trị viên.',
                ]);
            }

            throw ValidationException::withMessages([
                'login_id' => 'Thông tin đăng nhập không chính xác. (Lần sai: ' . $attempts . '/5)',
            ]);
        }

        // ✅ Nếu đúng mật khẩu
        return $user;
    }


    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->login_id) . '|' . request()->ip());
    }

    private function rules($fieldType)
    {
        if ($fieldType === 'email') {
            return [
                'login_id' => 'required|email|exists:users,email',
                'password' => 'required|min:5|max:45',
            ];
        }

        return [
            'login_id' => 'required|exists:users,account_code',
            'password' => 'required|min:5|max:45',
        ];
    }

    private function messages()
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
}
