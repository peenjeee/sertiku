<x-layouts.lembaga>
    <div class="space-y-6 lg:space-y-8">
        <!-- Welcome Banner -->
        <div class="welcome-banner relative rounded-2xl lg:rounded-3xl p-5 lg:p-8 overflow-hidden animate-fade-in">
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl text-white font-bold mb-1 lg:mb-2">Selamat Datang,
                        {{ Auth::user()->institution_name ?? Auth::user()->name }}!
                    </h1>
                    <p class="text-[#DBEAFE] text-sm lg:text-lg">Kelola dan terbitkan sertifikat digital dengan mudah
                    </p>
                </div>
                <div class="opacity-30 hidden sm:block">
                    <svg class="w-16 h-16 lg:w-24 lg:h-24" fill="none" stroke="white" viewBox="0 0 24 24"
                        stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
            </div>
        </div>

        @php
            $user = Auth::user();
            $isStarterPlan = $user->isStarterPlan();
            $certificateLimit = $user->getCertificateLimit();
            $certificatesUsed = $user->getCertificatesUsedThisMonth();
            $usagePercentage = $user->getUsagePercentage();
            $remainingCerts = $user->getRemainingCertificates();
            $activePackage = $user->getActivePackage();
            $isSubscriptionExpired = $user->isSubscriptionExpired();
            $isSubscriptionExpiringSoon = $user->isSubscriptionExpiringSoon();
            $daysRemaining = $user->getSubscriptionDaysRemaining();
            $previousPackageSlug = $user->package_id ? \App\Models\Package::find($user->package_id)?->slug : null;
            $billingCycleDaysRemaining = $user->getDaysRemainingInCycle();
            $billingCycleStart = $user->getBillingCycleStart();
        @endphp

        {{-- Subscription Expired Alert --}}
        @if($isSubscriptionExpired && $previousPackageSlug && $previousPackageSlug !== 'starter')
            <div class="bg-red-50 border-2 border-red-300 rounded-xl p-3 lg:p-6 animate-fade-in">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <div
                            class="hidden sm:flex w-10 h-10 lg:w-12 lg:h-12 bg-red-500 rounded-lg lg:rounded-xl items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-red-700 font-bold text-sm lg:text-lg">Langganan Telah Berakhir</h3>
                            <p class="text-red-600 text-xs lg:text-sm">
                                Langganan Anda telah berakhir. Anda kembali ke paket <strong>Normal</strong> dengan limit 50
                                sertifikat/bulan.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('checkout', $previousPackageSlug) }}"
                        class="flex items-center justify-center gap-2 w-full sm:w-auto px-4 py-2 lg:py-3 bg-red-500 text-white text-sm font-bold rounded-lg hover:bg-red-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Upgrade
                    </a>
                </div>
            </div>
        @endif

        {{-- Subscription Expiring Soon Warning --}}
        @if($isSubscriptionExpiringSoon && !$isStarterPlan)
            <div class="bg-yellow-50 border-2 border-yellow-300 rounded-xl p-3 lg:p-6 animate-fade-in">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <div
                            class="hidden sm:flex w-10 h-10 lg:w-12 lg:h-12 bg-yellow-500 rounded-lg lg:rounded-xl items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-yellow-700 font-bold text-sm lg:text-lg">Info</h3>
                            <p class="text-yellow-600 text-xs lg:text-sm">
                                Langganan <strong>{{ $activePackage->name }}</strong> Anda akan berakhir pada
                                <strong>{{ $user->subscription_expires_at->format('d M Y') }}</strong>
                            </p>
                        </div>
                    </div>
                    <!-- <a href="{{ route('checkout', $activePackage->slug) }}"
                                                                    class="flex items-center justify-center gap-2 w-full sm:w-auto px-4 py-2 lg:py-3 bg-yellow-500 text-white text-sm font-bold rounded-lg hover:bg-yellow-600 transition">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                                    </svg>
                                                                    Perpanjang Sekarang
                                                                </a> -->
                </div>
            </div>
        @endif

        {{-- Active Subscription Status (for paid plans) --}}
        @if(!$isStarterPlan && !$isSubscriptionExpired && !$isSubscriptionExpiringSoon && $user->subscription_expires_at)
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 lg:p-4 animate-fade-in">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 lg:w-10 lg:h-10 bg-emerald-500 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-emerald-800 font-semibold text-sm lg:text-base">
                                Paket {{ $activePackage->name }}
                            </p>
                            <p class="text-emerald-600 text-xs lg:text-sm">
                                Berakhir {{ $user->subscription_expires_at->format('d M Y') }} ({{ $daysRemaining }} hari
                                lagi)
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('checkout', $activePackage->slug) }}"
                        class="flex items-center justify-center gap-2 px-3 py-2 bg-emerald-500 text-white text-xs lg:text-sm font-medium rounded-lg hover:bg-emerald-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Perpanjang
                    </a>
                </div>
            </div>
        @endif

        <!-- Upgrade Banner for Starter Plan -->
        @if($isStarterPlan)
            <div class="bg-amber-50 border-2 border-amber-300 rounded-xl p-3 lg:p-6">
                <div class="flex flex-col gap-3">
                    <!-- Header -->
                    <div class="flex items-start gap-3">
                        <div
                            class="hidden sm:flex w-10 h-10 lg:w-12 lg:h-12 bg-amber-500 rounded-lg lg:rounded-xl items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-[#92400E] font-bold text-sm lg:text-lg">Paket Starter</h3>
                            <p class="text-[#B45309] text-xs lg:text-sm">
                                Upgrade ke <strong>Professional</strong> untuk mendapatkan hingga 5.000 sertifikat/bulan
                            </p>
                        </div>
                    </div>

                    <!-- Usage Progress -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[#92400E] text-xs lg:text-sm font-medium">Penggunaan</span>
                            <span class="text-[#B45309] text-xs lg:text-sm font-bold">{{ $certificatesUsed }} /
                                {{ $certificateLimit }} sertifikat</span>
                        </div>
                        <div class="w-full h-2 lg:h-3 bg-amber-200 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-500 rounded-full" style="width: {{ $usagePercentage }}%"></div>
                        </div>
                        @if($certificatesUsed > $certificateLimit)
                            <p class="text-red-600 text-xs font-bold mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Kuota melebihi limit! Reset dalam {{ $billingCycleDaysRemaining }} hari.
                            </p>
                        @elseif($remainingCerts <= 10)
                            <p class="text-red-600 text-xs font-bold mt-1 flex items-center gap-1"><svg class="w-3 h-3"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg> Sisa {{ $remainingCerts }} sertifikat!</p>
                        @endif
                    </div>

                    {{-- Upgrade Button --}}
                    <a href="{{ route('checkout', 'professional') }}"
                        class="flex items-center justify-center gap-2 w-full sm:w-auto px-4 py-2 lg:py-3 bg-amber-500 text-white text-sm font-bold rounded-lg hover:bg-amber-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        Upgrade ke Professional
                    </a>
                </div>
            </div>
        @endif

        <!-- Quick Action Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Terbitkan Sertifikat -->
            @if($user->canIssueCertificate())
                <a href="{{ route('lembaga.sertifikat.create') }}"
                    class="card-solid-blue rounded-xl lg:rounded-2xl p-4 lg:p-6 hover:scale-[1.02] transition cursor-pointer hover-lift animate-fade-in-up stagger-1">
                    <div class="flex items-center gap-3 lg:gap-4">
                        <div
                            class="icon-circle-blue w-10 h-10 lg:w-14 lg:h-14 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-[#1E3A8A] font-bold text-sm lg:text-lg">Terbitkan Sertifikat</h3>
                            <p class="text-[#3B82F6] text-xs lg:text-sm">Sisa kuota:
                                {{ $remainingCerts }}/{{ $certificateLimit }}
                            </p>
                        </div>
                    </div>
                </a>
            @else
                <div
                    class="relative rounded-xl lg:rounded-2xl p-4 lg:p-6 bg-red-50 border-2 border-red-200 opacity-80 cursor-not-allowed animate-fade-in-up stagger-1">
                    <div class="flex items-center gap-3 lg:gap-4">
                        <div
                            class="bg-red-400 w-10 h-10 lg:w-14 lg:h-14 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-red-700 font-bold text-sm lg:text-lg">Kuota Habis</h3>
                            <p class="text-red-500 text-xs lg:text-sm">{{ $certificatesUsed }}/{{ $certificateLimit }}
                                terpakai</p>
                        </div>
                    </div>
                    <a href="{{ url('/#harga') }}"
                        class="absolute inset-0 flex items-center justify-center bg-red-900/80 rounded-xl lg:rounded-2xl opacity-0 hover:opacity-100 transition-opacity">
                        <span class="text-white font-bold text-sm">Upgrade Paket →</span>
                    </a>
                </div>
            @endif

            <!-- Galeri Template -->
            <a href="{{ route('lembaga.template.index') }}"
                class="card-solid-green rounded-xl lg:rounded-2xl p-4 lg:p-6 hover:scale-[1.02] transition cursor-pointer hover-lift animate-fade-in-up stagger-2">
                <div class="flex items-center gap-3 lg:gap-4">
                    <div
                        class="icon-circle-green w-10 h-10 lg:w-14 lg:h-14 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[#166534] font-bold text-sm lg:text-lg">Galeri Sertifikat</h3>
                        <p class="text-[#22C55E] text-xs lg:text-sm">{{ $stats['total_templates'] ?? 0 }} template
                            tersimpan</p>
                    </div>
                </div>
            </a>

            <!-- Daftar Sertifikat -->
            <a href="{{ route('lembaga.sertifikat.index') }}"
                class="card-solid-purple rounded-xl lg:rounded-2xl p-4 lg:p-6 hover:scale-[1.02] transition cursor-pointer hover-lift animate-fade-in-up stagger-3">
                <div class="flex items-center gap-3 lg:gap-4">
                    <div
                        class="icon-circle-purple w-10 h-10 lg:w-14 lg:h-14 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[#7C3AED] font-bold text-sm lg:text-lg">Daftar Sertifikat</h3>
                        <p class="text-[#A855F7] text-xs lg:text-sm">Kelola sertifikat</p>
                    </div>
                </div>
            </a>

            @php
                $activePackage = $user->getActivePackage();
                $canAccessApi = $activePackage && in_array($activePackage->slug, ['professional', 'enterprise']);
            @endphp

            @if($canAccessApi)
                <!-- API Tokens - Only for Professional/Enterprise -->
                <!-- <a href="{{ route('lembaga.api-tokens.index') }}"
                                                                class="bg-cyan-50 border border-cyan-200 rounded-xl lg:rounded-2xl p-4 lg:p-6 hover:scale-[1.02] transition cursor-pointer hover-lift animate-fade-in-up stagger-4">
                                                                <div class="flex items-center gap-3 lg:gap-4">
                                                                    <div
                                                                        class="bg-cyan-500 w-10 h-10 lg:w-14 lg:h-14 rounded-full flex items-center justify-center flex-shrink-0">
                                                                        <svg class="w-5 h-5 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                                                        </svg>
                                                                    </div>
                                                                    <div>
                                                                        <h3 class="text-[#0E7490] font-bold text-sm lg:text-lg">API Tokens</h3>
                                                                        <p class="text-[#0891B2] text-xs lg:text-sm">Integrasi API</p>
                                                                    </div>
                                                                </div>
                                                            </a> -->
            @endif

            @if($canAccessApi)
                <!-- AI Template Generator -->
                <!-- <a href="{{ route('lembaga.template.ai') }}"
                                                                            class="bg-purple-50 border border-purple-200 rounded-xl lg:rounded-2xl p-4 lg:p-6 hover:scale-[1.02] transition cursor-pointer hover-lift animate-fade-in-up stagger-5">
                                                                            <div class="flex items-center gap-3 lg:gap-4">
                                                                                <div
                                                                                    class="bg-purple-500 w-10 h-10 lg:w-14 lg:h-14 rounded-full flex items-center justify-center flex-shrink-0">
                                                                                    <svg class="w-5 h-5 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                                                                    </svg>
                                                                                </div>
                                                                                <div>
                                                                                    <h3 class="text-[#7C3AED] font-bold text-sm lg:text-lg">AI Template</h3>
                                                                                    <p class="text-[#9333EA] text-xs lg:text-sm">Generate dengan AI</p>
                                                                                </div>
                                                                            </div>
                                                                        </a> -->

                <!-- Import Data (Bulk) -->
                @if($user->canIssueCertificate())
                    <a href="{{ route('lembaga.sertifikat.bulk') }}"
                        class="bg-indigo-50 border border-indigo-200 rounded-xl lg:rounded-2xl p-4 lg:p-6 hover:scale-[1.02] transition cursor-pointer hover-lift animate-fade-in-up stagger-6">
                        <div class="flex items-center gap-3 lg:gap-4">
                            <div
                                class="bg-indigo-500 w-10 h-10 lg:w-14 lg:h-14 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-[#4338CA] font-bold text-sm lg:text-lg">Import Data</h3>
                                <p class="text-[#6366F1] text-xs lg:text-sm">Upload Excel/CSV</p>
                            </div>
                        </div>
                    </a>
                @else
                    <div
                        class="relative rounded-xl lg:rounded-2xl p-4 lg:p-6 bg-red-50 border-2 border-red-200 opacity-80 cursor-not-allowed animate-fade-in-up stagger-6">
                        <div class="flex items-center gap-3 lg:gap-4">
                            <div
                                class="bg-red-400 w-10 h-10 lg:w-14 lg:h-14 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-red-700 font-bold text-sm lg:text-lg">Kuota Habis</h3>
                                <p class="text-red-500 text-xs lg:text-sm">{{ $certificatesUsed }}/{{ $certificateLimit }}
                                    terpakai</p>
                            </div>
                        </div>
                        <a href="{{ url('/#harga') }}"
                            class="absolute inset-0 flex items-center justify-center bg-red-900/80 rounded-xl lg:rounded-2xl opacity-0 hover:opacity-100 transition-opacity">
                            <span class="text-white font-bold text-sm">Upgrade Paket →</span>
                        </a>
                    </div>
                @endif
            @endif
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-2 gap-4 lg:gap-6 mt-6">
            <!-- Total Sertifikat -->
            <!-- <div class="stat-card-blue rounded-xl lg:rounded-2xl p-4 lg:p-6 hover-lift animate-fade-in-up stagger-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#1E40AF] text-xs lg:text-sm font-medium">Total Sertifikat</p>
                        <p class="text-[#1E3A8A] text-xl lg:text-3xl font-bold mt-1">
                            {{ number_format($stats['total_certificates'] ?? 0) }}
                        </p>
                    </div>
                    <div
                        class="stat-icon-blue w-10 h-10 lg:w-12 lg:h-12 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#3B82F6]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                </div>
            </div> -->

            <!-- Sertifikat Aktif -->
            <!-- <div class="stat-card-green rounded-xl lg:rounded-2xl p-4 lg:p-6 hover-lift animate-fade-in-up stagger-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#166534] text-xs lg:text-sm font-medium">Sertifikat Aktif</p>
                        <p class="text-[#15803D] text-xl lg:text-3xl font-bold mt-1">
                            {{ number_format($stats['active_certificates'] ?? 0) }}
                        </p>
                    </div>
                    <div
                        class="stat-icon-green w-10 h-10 lg:w-12 lg:h-12 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#22C55E]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div> -->

            <!-- Bulan Ini -->
            <div class="stat-card-orange rounded-xl lg:rounded-2xl p-4 lg:p-6 hover-lift animate-fade-in-up stagger-3">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#9A3412] text-xs lg:text-sm font-medium">Bulan Ini</p>
                        <p class="text-[#C2410C] text-xl lg:text-3xl font-bold mt-1">
                            {{ number_format($stats['certificates_this_month'] ?? 0) }}
                        </p>
                    </div>
                    <div
                        class="stat-icon-orange w-10 h-10 lg:w-12 lg:h-12 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#F97316]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Verifikasi -->
            <div class="stat-card-purple rounded-xl lg:rounded-2xl p-4 lg:p-6 hover-lift animate-fade-in-up stagger-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#6B21A8] text-xs lg:text-sm font-medium">Total Verifikasi</p>
                        <p class="text-[#7C3AED] text-xl lg:text-3xl font-bold mt-1">
                            {{ number_format($stats['total_verifications'] ?? 0) }}
                        </p>
                    </div>
                    <div
                        class="stat-icon-purple w-10 h-10 lg:w-12 lg:h-12 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#A855F7]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usage Progress Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mt-6">
            @php
                $totalCerts = $stats['total_certificates'] ?? 0;
                $blockchainLimit = $user->getBlockchainLimit();
                $ipfsLimit = $user->getIpfsLimit();
                $cycleStart = $user->getBillingCycleStart() ?? now()->startOfYear();
                $blockchainCerts = $user->certificates()
                    ->whereNotNull('blockchain_tx_hash')
                    ->where('created_at', '>=', $cycleStart)
                    ->count();
                $ipfsCerts = $user->certificates()
                    ->whereNotNull('ipfs_cid')
                    ->where('created_at', '>=', $cycleStart)
                    ->count();
                $blockchainPercentage = $blockchainLimit > 0 ? round(($blockchainCerts / $blockchainLimit) * 100) : 0;
                $ipfsPercentage = $ipfsLimit > 0 ? round(($ipfsCerts / $ipfsLimit) * 100) : 0;

                $remainingBlockchain = max(0, $blockchainLimit - $blockchainCerts);
                $remainingIpfs = max(0, $ipfsLimit - $ipfsCerts);
            @endphp

            <!-- Certificate Quota Progress -->
            <div class="glass-card rounded-xl lg:rounded-2xl p-4 lg:p-6 animate-fade-in-up">
                <div class="flex items-center gap-3 mb-4">
                    <div
                        class="w-10 h-10 lg:w-12 lg:h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-gray-800 font-bold text-sm lg:text-base">Kuota Sertifikat</h3>
                        <p class="text-gray-500 text-xs">Penggunaan bulan ini</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ $certificatesUsed }} / {{ $certificateLimit }}</span>
                        <span class="text-blue-600 font-bold">{{ $usagePercentage }}%</span>
                    </div>
                    <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all duration-500"
                            style="width: {{ min($usagePercentage, 100) }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500">Sisa {{ $remainingCerts }} sertifikat tersedia</p>
                </div>
            </div>

            <!-- Blockchain Usage Progress -->
            <div class="glass-card rounded-xl lg:rounded-2xl p-4 lg:p-6 animate-fade-in-up">
                <div class="flex items-center gap-3 mb-4">
                    <div
                        class="w-10 h-10 lg:w-12 lg:h-12 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-purple-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-gray-800 font-bold text-sm lg:text-base">Blockchain</h3>
                        <p class="text-gray-500 text-xs">Sertifikat tersimpan di blockchain</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ $blockchainCerts }} / {{ $blockchainLimit }}</span>
                        <span class="text-purple-600 font-bold">{{ $blockchainPercentage }}%</span>
                    </div>
                    <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-purple-500 to-purple-600 rounded-full transition-all duration-500"
                            style="width: {{ $blockchainPercentage }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500">Sisa {{ $remainingBlockchain}} on-chain verifikasi
                    </p>
                </div>
            </div>

            <!-- IPFS Usage Progress -->
            <div class="glass-card rounded-xl lg:rounded-2xl p-4 lg:p-6 animate-fade-in-up">
                <div class="flex items-center gap-3 mb-4">
                    <div
                        class="w-10 h-10 lg:w-12 lg:h-12 bg-teal-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-teal-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-gray-800 font-bold text-sm lg:text-base">IPFS Storage</h3>
                        <p class="text-gray-500 text-xs">Sertifikat tersimpan di IPFS</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ $ipfsCerts }} / {{ $ipfsLimit }}</span>
                        <span class="text-teal-600 font-bold">{{ $ipfsPercentage }}%</span>
                    </div>
                    <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-teal-500 to-teal-600 rounded-full transition-all duration-500"
                            style="width: {{ $ipfsPercentage }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500">Sisa {{ $remainingIpfs }} desentralisasi metadata</p>
                </div>
            </div>
        </div>

        <!-- Recent Certificates Card -->
        <div class="glass-card rounded-xl lg:rounded-2xl overflow-hidden animate-fade-in">
            <!-- Header -->
            <div class="header-solid p-4 lg:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#3B82F6]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    <div>
                        <h2 class="text-[#1E293B] text-base lg:text-xl font-bold">Sertifikat Terbaru</h2>
                        <p class="text-[#64748B] text-xs lg:text-sm">{{ $stats['recent_certificates']->count() }}
                            sertifikat yang baru diterbitkan</p>
                    </div>
                </div>
                <a href="{{ route('lembaga.sertifikat.index') }}"
                    class="flex items-center justify-center gap-2 px-3 lg:px-4 py-2 lg:py-2.5 bg-white border border-[#E2E8F0] rounded-lg text-[#1E293B] text-xs lg:text-sm hover:bg-gray-50 transition shadow-sm">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>

            <!-- Content -->
            <div class="p-4 lg:p-6">
                <!-- Search & Filter -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 mb-4 lg:mb-6">
                    <div class="relative flex-1 lg:max-w-xl">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#94A3B8]" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="search-input" placeholder="Cari nama atau ID..." onkeyup="filterTable()"
                            class="w-full pl-10 pr-4 py-2.5 lg:py-3 bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg text-sm text-[#1E293B] placeholder-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="flex items-center gap-2 overflow-x-auto" id="filter-buttons">
                        <button onclick="setFilter('all')" data-filter="all"
                            class="filter-btn active px-3 lg:px-4 py-2 lg:py-3 bg-[#1E3A8F] text-white text-xs lg:text-sm rounded-lg font-medium whitespace-nowrap">Semua</button>
                        <button onclick="setFilter('active')" data-filter="active"
                            class="filter-btn px-3 lg:px-4 py-2 lg:py-3 bg-white border border-[#E2E8F0] text-[#1E293B] text-xs lg:text-sm rounded-lg whitespace-nowrap">Aktif</button>
                        <button onclick="setFilter('expired')" data-filter="expired"
                            class="filter-btn px-3 lg:px-4 py-2 lg:py-3 bg-white border border-[#E2E8F0] text-[#1E293B] text-xs lg:text-sm rounded-lg whitespace-nowrap">Kadaluarsa</button>
                        <button onclick="setFilter('revoked')" data-filter="revoked"
                            class="filter-btn px-3 lg:px-4 py-2 lg:py-3 bg-white border border-[#E2E8F0] text-[#1E293B] text-xs lg:text-sm rounded-lg whitespace-nowrap">Dicabut</button>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-[#E2E8F0]">
                                <th class="text-left py-3 px-4 text-[#64748B] text-sm font-medium">Penerima</th>
                                <th class="text-left py-3 px-4 text-[#64748B] text-sm font-medium">Program</th>
                                <th class="text-left py-3 px-4 text-[#64748B] text-sm font-medium">ID Kredensial</th>
                                <th class="text-left py-3 px-4 text-[#64748B] text-sm font-medium">Tanggal</th>
                                <th class="text-left py-3 px-4 text-[#64748B] text-sm font-medium">Status</th>
                                <th class="text-right py-3 px-4 text-[#64748B] text-sm font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="certificate-tbody">
                            @forelse($stats['recent_certificates'] ?? [] as $cert)
                                @php
                                    $solidColors = ['bg-[#3B82F6]', 'bg-[#10B981]', 'bg-[#8B5CF6]', 'bg-[#F97316]', 'bg-[#06B6D4]'];
                                    $colorIndex = $loop->index % count($solidColors);
                                    $initials = collect(explode(' ', $cert->recipient_name))->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->join('');

                                    // Check if recipient is registered in system
                                    $registeredUser = null;
                                    $avatarUrl = null;

                                    if ($cert->recipient_email) {
                                        $registeredUser = \App\Models\User::where('email', $cert->recipient_email)->first();

                                        if ($registeredUser && $registeredUser->avatar && (str_starts_with($registeredUser->avatar, '/storage/') || str_starts_with($registeredUser->avatar, 'http'))) {
                                            // User is registered and has custom or Google avatar
                                            $avatarUrl = $registeredUser->avatar;
                                        } else {
                                            // User not registered or no avatar - use UI Avatars
                                            $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($cert->recipient_name) . '&email=' . urlencode($cert->recipient_email) . '&background=random&color=fff&bold=true&size=40';
                                        }
                                    } else {
                                        // No email - use UI Avatars with name only
                                        $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($cert->recipient_name) . '&background=random&color=fff&bold=true&size=40';
                                    }

                                    // Calculate virtual status for filter
                                    $virtualStatus = $cert->status;
                                    if ($cert->status !== 'revoked' && $cert->expire_date && $cert->expire_date < now()) {
                                        $virtualStatus = 'expired';
                                    }
                                @endphp
                                <tr class="border-b border-[#F1F5F9] hover:bg-[#F8FAFC] cert-row"
                                    data-status="{{ $virtualStatus }}" data-name="{{ strtolower($cert->recipient_name) }}"
                                    data-cert="{{ strtolower($cert->certificate_number) }}">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full {{ $solidColors[$colorIndex] }} flex items-center justify-center overflow-hidden">
                                                @if($avatarUrl)
                                                    <img src="{{ $avatarUrl }}" alt="Avatar" class="w-full h-full object-cover">
                                                @else
                                                    <span class="text-white font-bold text-sm">{{ $initials }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-[#1E293B] font-medium">{{ $cert->recipient_name }}</p>
                                                <p class="text-[#94A3B8] text-sm">
                                                    {{ $cert->recipient_email ?? 'Email tidak tersedia' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-[#1E293B]">{{ $cert->course_name }}</td>
                                    <td class="py-4 px-4">
                                        <code
                                            class="text-[#3B82F6] bg-[#EFF6FF] px-2 py-1 rounded text-sm whitespace-nowrap">{{ Str::limit($cert->certificate_number, 14) }}</code>
                                    </td>
                                    <td class="py-4 px-4 text-[#64748B]">{{ $cert->issue_date->format('d M Y') }}</td>
                                    <td class="py-4 px-4">
                                        @if($cert->status === 'revoked')
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#FEF2F2] text-[#DC2626] text-sm rounded-full font-medium">
                                                <span class="w-1.5 h-1.5 bg-[#DC2626] rounded-full"></span>
                                                Dicabut
                                            </span>
                                        @elseif($cert->expire_date && $cert->expire_date < now())
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#FEF3C7] text-[#D97706] text-sm rounded-full font-medium">
                                                <span class="w-1.5 h-1.5 bg-[#D97706] rounded-full"></span>
                                                Kadaluarsa
                                            </span>
                                        @elseif($cert->status === 'active')
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#ECFDF5] text-[#059669] text-sm rounded-full font-medium">
                                                <span class="w-1.5 h-1.5 bg-[#059669] rounded-full"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#F1F5F9] text-[#475569] text-sm rounded-full font-medium">
                                                <span class="w-1.5 h-1.5 bg-[#475569] rounded-full"></span>
                                                {{ ucfirst($cert->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <a href="{{ route('lembaga.sertifikat.show', $cert->id) }}" target="_blank"
                                            class="text-[#3B82F6] hover:text-[#1D4ED8] text-sm font-medium">Lihat</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <p class="text-gray-600 font-medium mb-1">Belum ada sertifikat</p>
                                            <p class="text-gray-400 text-sm mb-4">Mulai terbitkan sertifikat pertama Anda
                                            </p>
                                            <a href="{{ route('lembaga.sertifikat.create') }}"
                                                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">Terbitkan
                                                Sekarang</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Recent Certificates Table (Existing) -->
            <div class="glass-card rounded-xl lg:rounded-2xl overflow-hidden animate-fade-in-up stagger-2">
                <!-- ... existing content ... -->
            </div>
        </div>

        <!-- Chart Section -->
        <div class="glass-card rounded-xl lg:rounded-2xl p-4 lg:p-6 mt-6 animate-fade-in-up stagger-3">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg lg:text-xl font-bold text-gray-800">Statistik Penerbitan</h2>
                    <p class="text-sm text-gray-500">Jumlah sertifikat diterbitkan per bulan tahun {{ date('Y') }}
                    </p>
                </div>
            </div>
            <div class="relative h-64 lg:h-80 w-full">
                <canvas id="certificateChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('certificateChart').getContext('2d');

            // Prepare data from PHP
            const monthlyCounts = @json($stats['monthly_counts'] ?? []);
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

            // Map data to all 12 months (fill 0 for missing months)
            const data = Array(12).fill(0).map((_, i) => monthlyCounts[i + 1] || 0);

            // Create gradient
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.5)'); // Blue start
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)'); // Transparent end

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthNames,
                    datasets: [{
                        label: 'Sertifikat Diterbitkan',
                        data: data,
                        borderColor: '#3B82F6', // Blue-500
                        backgroundColor: gradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#FFFFFF',
                        pointBorderColor: '#3B82F6',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1E293B',
                            titleColor: '#F8FAFC',
                            bodyColor: '#F8FAFC',
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                label: function (context) {
                                    return context.parsed.y + ' Sertifikat';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    family: "'Poppins', sans-serif",
                                    size: 11
                                },
                                color: '#64748B'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: "'Poppins', sans-serif",
                                    size: 11
                                },
                                color: '#64748B'
                            }
                        }
                    }
                }
            });
        });
    </script>

    {{-- Filter and Search JavaScript --}}
    <script>
        let currentFilter = 'all';

        function setFilter(filter) {
            currentFilter = filter;

            // Update button styles
            document.querySelectorAll('.filter-btn').forEach(btn => {
                if (btn.dataset.filter === filter) {
                    btn.classList.remove('bg-white', 'border', 'border-[#E2E8F0]', 'text-[#1E293B]');
                    btn.classList.add('bg-[#1E3A8F]', 'text-white');
                } else {
                    btn.classList.remove('bg-[#1E3A8F]', 'text-white');
                    btn.classList.add('bg-white', 'border', 'border-[#E2E8F0]', 'text-[#1E293B]');
                }
            });

            filterTable();
        }

        function filterTable() {
            const searchTerm = document.getElementById('search-input')?.value.toLowerCase() || '';
            const rows = document.querySelectorAll('.cert-row');

            rows.forEach(row => {
                const status = row.dataset.status;
                const name = row.dataset.name || '';
                const cert = row.dataset.cert || '';

                // Check filter
                let matchesFilter = currentFilter === 'all' || status === currentFilter;

                // Check search
                let matchesSearch = searchTerm === '' ||
                    name.includes(searchTerm) ||
                    cert.includes(searchTerm);

                // Show/hide row
                if (matchesFilter && matchesSearch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>

</x-layouts.lembaga>