<x-layout guest>

    <div class="flex flex-1">

        {{-- LEFT BRANDING PANEL --}}
        <div class="hidden md:flex md:w-[65%] relative overflow-hidden">

            {{-- BACKDROP IMAGE --}}
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/background.png') }}')"></div>

            {{-- DARK OVERLAY --}}
            <div class="absolute inset-0 bg-brand-black/50"></div>

            {{-- CONTENT --}}
            <div class="relative z-10 flex flex-col justify-between p-12 text-neutral-0 w-full">

                {{-- TOP TAGLINE --}}
                <div>
                    <p class="text-xs tracking-widest text-neutral-400 font-heading">BUILT FOR</p>
                    <h2 class="text-2xl font-heading font-bold leading-tight">
                        <span class="text-brand-yellow">A SWEETER</span><br>
                        TOMORROW
                    </h2>
                    <div class="w-10 h-1 bg-brand-yellow mt-3"></div>
                </div>

                {{-- LOGO + TAGLINE --}}
                <div class="flex flex-col items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Four Stripes Logo" class="h-130 -mb-6 select-none" draggable="false">
                    <p class="text-center text-neutral-300 tracking-wide font-heading font-semibold text-xl">
                        ENGINEERING THE FUTURE OF CACAO
                    </p>
                </div>

                {{-- BOTTOM ICON ROW --}}
                <div>
                    <div class="border-t border-neutral-800 mb-4"></div>
                    <div class="flex justify-center gap-8 text-xs text-neutral-300 font-heading tracking-wide">
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/icons/yellow-gear.svg') }}" class="w-4 h-4" alt="">
                            MACHINES
                        </div>
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/icons/yellow-wrench.svg') }}" class="w-4 h-4" alt="">
                            TOOLS
                        </div>
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/icons/yellow-bulb.svg') }}" class="w-4 h-4" alt="">
                            SOLUTIONS
                        </div>
                    </div>
                </div>

            </div>

        </div>

        {{-- RIGHT LOGIN FORM PANEL --}}
        <div class="w-full md:w-[35%] flex items-center justify-center bg-neutral-0 p-8">
            <div class="w-full max-w-lg">

                {{-- HEADER --}}
                <p class="text-xs tracking-widest text-neutral-600 font-heading mb-2">WELCOME TO</p>
                <h1 class="text-3xl font-heading font-extrabold text-neutral-900">FOUR STRIPES</h1>
                <p class="text-xs tracking-widest text-brand-yellow-deep font-heading font-semibold mt-1">
                    EQUIPMENT AND MACHINES CORP.
                </p>
                <div class="w-10 h-1 bg-brand-yellow mt-3 mb-4"></div>

                {{-- LOGIN FORM --}}
                <form method="POST" action="{{ route('login.attempt') }}"   autocomplete="new-password" class="space-y-4">
                    @csrf

                    {{-- USERNAME (EMAIL) FIELD --}}
                    <div>
                        <x-input label="Email" name="email" type="email" placeholder="Enter your email">
                            <x-slot:icon>
                                <img src="{{ asset('images/icons/grey-person.svg') }}" class="w-4 h-4" alt="">
                            </x-slot:icon>
                        </x-input>
                        <x-error name="email" />
                    </div>

                    {{-- PASSWORD FIELD --}}
                    <div>
                        <label for="password" class="block text-sm font-semibold text-neutral-900 mb-1 font-body">
                            Password
                        </label>
                        <div class="relative">

                            {{-- LOCK ICON (LEFT) --}}
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-600">
                                <img src="{{ asset('images/icons/grey-lock.svg') }}" class="w-4 h-4" alt="">
                            </div>

                            {{-- INPUT --}}
                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Enter your password"
                                autocomplete="new-password"
                                class="w-full border border-neutral-200 bg-neutral-100 rounded-lg py-3 pl-10 pr-10 text-sm font-body text-neutral-900 focus:outline-none focus:ring-2 focus:ring-brand-yellow"
                            >

                            {{-- TOGGLE BUTTON (RIGHT) --}}
                            <button
                                type="button"
                                onclick="
                                    var input = document.getElementById('password');
                                    var showIcon = document.getElementById('eye-show');
                                    var hideIcon = document.getElementById('eye-hide');
                                    if (input.type === 'password') {
                                        input.type = 'text';
                                        showIcon.classList.add('hidden');
                                        hideIcon.classList.remove('hidden');
                                    } else {
                                        input.type = 'password';
                                        showIcon.classList.remove('hidden');
                                        hideIcon.classList.add('hidden');
                                    }
                                "
                                class="absolute inset-y-0 right-0 flex items-center pr-3"
                            >
                                <img id="eye-show" src="{{ asset('images/icons/black-show.svg') }}" class="w-4 h-5" alt="Show password">
                                <img id="eye-hide" src="{{ asset('images/icons/black-eye-hide.svg') }}" class="w-4 h-5 hidden" alt="Hide password">
                            </button>

                        </div>
                        <x-error name="password" />
                    </div>

                    {{-- LOGIN BUTTON --}}
                    <x-button type="submit" class="w-full">
                        LOGIN
                    </x-button>

                </form>

                {{-- FOOTER --}}
                <div class="border-t border-neutral-200 mt-8 pt-4">
                    <p class="text-center text-[10px] tracking-widest text-neutral-400 font-heading">
                        QUALITY EQUIPMENT FOR A STRONGER CACAO INDUSTRY
                    </p>
                </div>

            </div>
        </div>

    </div>

</x-layout>