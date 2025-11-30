<x-guest-layout>
    <div class="container-fluid p-0">
        <div class="row m-0">
            <div class="col-12 p-0">
                <div class="login-card">
                    <div>
                        <div class="login-main">
                            <!-- Logo -->
                            <div class="text-center mb-4">
                                <img src="{{ asset('images/logo.png') }}" alt="Emma Luxury" class="login-logo">
                            </div>

                            <form class="theme-form" method="POST" action="{{ route('login') }}">
                                @csrf

                                <h4>Connexion à votre compte</h4>
                                <p>Entrez votre email et mot de passe pour vous connecter</p>

                                <!-- Session Status -->
                                @if (session('status'))
                                    <div class="alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <!-- Validation Errors -->
                                @if ($errors->any())
                                    <div class="alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Email -->
                                <div class="form-group">
                                    <label class="col-form-label" for="email">Adresse Email</label>
                                    <input
                                        class="form-control"
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autofocus
                                        placeholder="votre@email.com"
                                    >
                                </div>

                                <!-- Password -->
                                <div class="form-group">
                                    <label class="col-form-label" for="password">Mot de passe</label>
                                    <div class="form-input">
                                        <input
                                            class="form-control"
                                            type="password"
                                            id="password"
                                            name="password"
                                            required
                                            autocomplete="current-password"
                                            placeholder="••••••••"
                                        >
                                        <div class="show-hide">
                                            <span>Afficher</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Remember Me & Forgot Password -->
                                <div class="form-group mb-0">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                id="remember_me"
                                                type="checkbox"
                                                name="remember"
                                            >
                                            <label class="form-check-label" for="remember_me">
                                                Se souvenir de moi
                                            </label>
                                        </div>

                                        @if (Route::has('password.request'))
                                            <a class="link" href="{{ route('password.request') }}">
                                                Mot de passe oublié ?
                                            </a>
                                        @endif
                                    </div>

                                    <!-- Submit Button -->
                                    <button class="btn btn-primary btn-block w-100" type="submit">
                                        <i class="fa fa-sign-in-alt me-2"></i>Se connecter
                                    </button>
                                </div>

                                <!-- Register Link -->
                                @if (Route::has('register'))
                                    <p class="mt-4 mb-0 text-center">
                                        Vous n'avez pas de compte ?
                                        <a class="link ms-2" href="{{ route('register') }}">
                                            Créer un compte
                                        </a>
                                    </p>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
