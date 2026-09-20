<x-layout guest>

    <div class="flex flex-1">

        {{-- LEFT BRANDING PANEL --}}
        <div class="hidden md:flex md:w-[65%] relative overflow-hidden select-none">

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
                    <img src="{{ asset('images/logo.png') }}" alt="Four Stripes Logo" class="h-120 -mb-10" draggable="false">
                    <p class="text-center text-neutral-300 tracking-wide font-heading font-semibold">
                        CACAO MACHINES<br>
                        BETTER POSSIBILITIES
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
            <div class="w-full max-w-md">

                {{-- HEADER --}}
                <p class="text-xs tracking-widest text-neutral-600 font-heading mb-2 select-none">WELCOME TO</p>
                <h1 class="text-3xl font-heading font-extrabold text-neutral-900 select-none">FOUR STRIPES</h1>
                <p class="text-xs tracking-widest text-brand-yellow-deep font-heading font-semibold mt-1 select-none">
                    EQUIPMENT AND MACHINES CORP.
                </p>
                <div class="w-10 h-1 bg-brand-yellow mt-3 mb-4"></div>

                <p class="text-sm text-neutral-600 mb-6 select-none">
                    Manage sales, inventory, and operations. 
                    <br>Access your terminal below.
                </p>

                {{-- LOGIN FORM --}}
                <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
                    @csrf

                    {{-- USERNAME (EMAIL) FIELD --}}
                    <div>
                        <x-input label="Username" name="email" type="email" placeholder="Enter your username">
                            <x-slot:icon>
                                <img src="{{ asset('images/icons/grey-person.svg') }}" class="w-4 h-4" alt="">
                            </x-slot:icon>
                        </x-input>
                        <x-error name="email" />
                    </div>

                    {{-- PASSWORD FIELD --}}
                    <div>
                        <x-input label="Password" name="password" type="password" placeholder="Enter your password">
                            <x-slot:icon>
                                <img src="{{ asset('images/icons/grey-lock.svg') }}" class="w-4 h-4" alt="">
                            </x-slot:icon>
                        </x-input>
                        <x-error name="password" />
                    </div>

                    {{-- FORGOT PASSWORD --}}
                    <div class="flex items-center justify-end text-sm">
                        <a href="#" class="text-brand-yellow-deep font-semibold hover:underline">Forgot password?</a>
                    </div>

                    {{-- LOGIN BUTTON --}}
                    <x-button type="submit">
                        Login &rarr;
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