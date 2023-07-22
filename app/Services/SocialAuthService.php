<?php

namespace App\Services;

use Laravel\Socialite\Facades\Socialite;


class SocialAuthService {
  public function redirectToProvider($provider) {
    return Socialite::driver($provider)->stateless()->redirect();
  }

  public function handleProviderCallback($provider) {
    return Socialite::driver($provider)->stateless()->user();
  }
}
