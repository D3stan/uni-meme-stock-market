<x-base>
    <main class="max-w-2xl mx-4 md:mx-auto space-y-12 my-6 pb-24">
        
        {{-- Test Input Component --}}
        <section>
            <h1 class="text-2xl font-bold text-text-main mb-6">Test Input Components</h1>
            
            <div class="space-y-6">
                {{-- Input base --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-text-main">Email</label>
                    <x-forms.input 
                        name="email" 
                        type="email" 
                        icon="school" 
                        placeholder="nome.cognome@studio.unibo.it" 
                    />
                </div>
                
                {{-- Input con valore --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-text-main">Password</label>
                    <x-forms.input 
                        name="username" 
                        icon="lock" 
                        type="password" 
                        placeholder="Password"
                    />
                </div>
                
                {{-- Input disabled--}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-text-main">CFU (Disabled)</label>
                    <x-forms.input 
                        name="cfu" 
                        value="1000" 
                        disabled 
                    />
                </div>

                {{-- Text Input (Alternative) --}}
                <x-forms.textinput id="test-textinput" name="nickname" label="Nickname" prefix="@" placeholder="username" />
            </div>
        </section>

        {{-- Test Advanced Form Components --}}
        <section>
            <h1 class="text-2xl font-bold text-text-main mb-6">Test Advanced Form Components</h1>
            <div class="space-y-6">
                <x-forms.textarea id="test-textarea" name="bio" label="Biography" placeholder="Tell us about yourself" helpText="Max 500 chars" />
                
                <x-forms.select id="test-select" name="role" label="Role" :options="[['value' => 'admin', 'text' => 'Admin'], ['value' => 'user', 'text' => 'User']]" />
                
                <x-forms.datepicker id="test-date" name="event_date" label="Event Date" />
                
                <x-forms.toggle id="test-toggle" name="notifications" text="Enable Notifications" />
                
                <div>
                    <label class="block mb-4 text-sm font-medium text-text-main">OTP Input</label>
                    <x-forms.otp-input length="4" />
                </div>
                
                <x-forms.filepicker name="avatar" />
            </div>
        </section>

        {{-- Test Button Component --}}
        <section>
            <h1 class="text-2xl font-bold text-text-main mb-6">Test Button Component</h1>
            
            <div class="space-y-6">
                {{-- Variants --}}
                <div>
                    <h3 class="text-sm font-medium text-text-muted mb-3">Variants</h3>
                    <div class="flex flex-wrap gap-3">
                        <x-forms.button variant="primary">Primary</x-forms.button>
                        <x-forms.button variant="secondary">Secondary</x-forms.button>
                        <x-forms.button variant="success">Success</x-forms.button>
                        <x-forms.button variant="danger">Danger</x-forms.button>
                        <x-forms.button variant="outline">Outline</x-forms.button>
                    </div>
                </div>

                {{-- Size --}}
                <div>
                    <h3 class="text-sm font-medium text-text-muted mb-3">Size</h3>
                    <div class="flex flex-wrap items-center gap-3">
                        <x-forms.button size="sm">Small</x-forms.button>
                        <x-forms.button size="md">Medium</x-forms.button>
                        <x-forms.button size="lg">Large</x-forms.button>
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <h3 class="text-sm font-medium text-text-muted mb-3">Status</h3>
                    <div class="flex flex-wrap gap-3">
                        <x-forms.button>Attivo</x-forms.button>
                        <x-forms.button disabled>Disabilitato</x-forms.button>
                    </div>
                </div>
            </div>
        </section>

        {{-- Test Badge Change Component --}}
        <section>
            <h1 class="text-2xl font-bold text-text-main mb-6">Test Badge Price Variation</h1>
            
            <div class="space-y-6">
                {{-- Variations positive/negative/neutral --}}
                <div>
                    <h3 class="text-sm font-medium text-text-muted mb-3">Variations</h3>
                    <div class="flex flex-wrap gap-3">
                        <x-ui.badge-change :value="5.43" />
                        <x-ui.badge-change :value="0.00" />
                        <x-ui.badge-change :value="-8.92" />
                    </div>
                </div>

                {{-- Size --}}
                <div>
                    <h3 class="text-sm font-medium text-text-muted mb-3">Size</h3>
                    <div class="flex flex-wrap items-center gap-3">
                        <x-ui.badge-change :value="3.5" size="sm" />
                        <x-ui.badge-change :value="3.5" size="md" />
                        <x-ui.badge-change :value="3.5" size="lg" />
                    </div>
                </div>
            </div>
        </section>

        {{-- Test UI Components --}}
        <section>
            <h1 class="text-2xl font-bold text-text-main mb-6">Test UI Components</h1>
            <div class="space-y-6">
                <x-ui.empty-state icon="inbox" title="No Messages" message="Your inbox is empty." />
                
                <div class="grid grid-cols-2 gap-4">
                    <x-ui.stat-card title="Total Users" value="1,234" />
                    <x-ui.trend-card title="Revenue" value="$50k" variation="12.5" />
                </div>

                <x-ui.options-card title="Security" description="Manage your password and 2FA" icon="security" />

                @php
                    $tableCols = [
                        ['label' => 'Name', 'key' => 'name'],
                        ['label' => 'Role', 'key' => 'role'],
                        ['label' => 'Status', 'render' => fn($row) => $row['active'] ? '<span class="text-green-500 font-bold">Active</span>' : '<span class="text-red-500 font-bold">Inactive</span>']
                    ];
                    $tableRows = [
                        ['name' => 'John Doe', 'role' => 'Admin', 'active' => true],
                        ['name' => 'Jane Smith', 'role' => 'User', 'active' => false],
                    ];
                @endphp
                <x-ui.table caption="Users List" :columns="$tableCols" :rows="$tableRows" />
            </div>
        </section>

        {{-- Test Modals & Toasts --}}
        <section>
            <h1 class="text-2xl font-bold text-text-main mb-6">Test Modals & Toasts</h1>
            <div class="flex flex-wrap gap-4">
                <x-forms.button onclick="showModal('confirmation-modal')">Confirm Modal</x-forms.button>
                <x-forms.button onclick="showNotificationModal('success', 'Great!', 'Operation successful.')">Notify Modal</x-forms.button>
                <x-forms.button onclick="window.showToast('success', 'This is a toast message!')">Show Toast</x-forms.button>
            </div>
            
            <x-ui.confirmation-modal id="confirmation-modal" />
            <x-ui.notify-modal id="notificationModal" />
            <x-ui.toast />
        </section>

        {{-- Test Meme Card Components --}}
        <section>
            <h1 class="text-2xl font-bold text-text-main mb-6">Test Meme Card Components</h1>
            
            <div class="space-y-8">
                {{-- Compact Card (per Landing/Profilo) --}}
                <div>
                    <h3 class="text-sm font-medium text-text-muted mb-3">Compact Card(Landing/Profile)</h3>
                    <div class="space-y-3">
                        <x-meme.card-compact 
                            name="Meme Everywhere"
                            image="storage/test/meme.webp" 
                            ticker="MEVR"
                            :price="42.50"
                            :change="15.8"
                        />
                        
                        <x-meme.card-compact 
                            name="Un segreto è un segreto"
                            image="storage/test/meme.jpeg" 
                            ticker="SCRT"
                            :price="127.30"
                            :change="-3.2"
                        />
                    </div>
                </div>

                {{-- Extended Card (Marketplace) --}}
                <div>
                    <h3 class="text-sm font-medium text-text-muted mb-3">Extended Card (Marketplace)</h3>
                    <div class="space-y-4">
                        <x-meme.card 
                            name="Un segreto è un segreto"
                            image="storage/test/meme.jpeg" 
                            ticker="SCRT"
                            :price="42.50"
                            :change="15.8"
                            creatorName="Mario Rossi"
                            status="new"
                            tradeUrl="#"
                            alt="Meme alt text"
                        />
                    </div>
                </div>

                {{-- Skeleton --}}
                <div>
                    <h3 class="text-sm font-medium text-text-muted mb-3">Skeleton Loading</h3>
                    <x-meme.skeleton />
                </div>
            </div>
        </section>

        {{-- Test Chip Component --}}
        <section>
            <h1 class="text-2xl font-bold text-text-main mb-6">Test Chip Component</h1>
            
            <div class="space-y-6">
                {{-- Base Variants --}}
                <div>
                    <h3 class="text-sm font-medium text-text-muted mb-3">Variants</h3>
                    <div class="flex flex-wrap gap-3">
                        <x-ui.chip variant="white">Tutti</x-ui.chip>
                        <x-ui.chip variant="outline">Outline</x-ui.chip>
                        <x-ui.chip variant="success">Success</x-ui.chip>
                    </div>
                </div>

                {{-- With/Without emoji --}}
                <div>
                    <h3 class="text-sm font-medium text-text-muted mb-3">With/Without Emoji</h3>
                    <div class="flex flex-wrap gap-3">
                        <x-ui.chip variant="outline" icon="🚀">Top Gainer</x-ui.chip>
                        <x-ui.chip variant="outline">Top Gainer</x-ui.chip>
                    </div>
                </div>
            </div>
        </section>

        {{-- Test Leaderboard Components --}}
        <section>
            <h1 class="text-2xl font-bold text-text-main mb-6">Test Leaderboard Components</h1>
            <div class="space-y-6">
                <x-leaderboard.podium 
                    :first="['username' => 'Winner', 'avatar' => 'https://ui-avatars.com/api/?name=W', 'net_worth' => 50000]"
                    :second="['username' => 'RunnerUp', 'avatar' => 'https://ui-avatars.com/api/?name=R', 'net_worth' => 40000]"
                    :third="['username' => 'Third', 'avatar' => 'https://ui-avatars.com/api/?name=T', 'net_worth' => 30000]"
                />
                
                <x-leaderboard.user-position-card rank="42" username="You" netWorth="15000" percentile="5" />
                
                <x-leaderboard.user-rank-row rank="5" username="Another User" netWorth="25000" />
            </div>
        </section>

        {{-- Test Portfolio Components --}}
        <section>
            <h1 class="text-2xl font-bold text-text-main mb-6">Test Portfolio Components</h1>
            <div class="space-y-6">
                <x-portfolio.net-worth-hero netWorth="12345.67" dailyChange="123.45" dailyChangePct="1.2" />
                <x-portfolio.allocation-chart invested="8000" liquid="4345.67" />
            </div>
        </section>

        {{-- Test Profile Components --}}
        <section>
            <h1 class="text-2xl font-bold text-text-main mb-6">Test Profile Components</h1>
            <div class="space-y-6 bg-surface-50 p-4 rounded-xl">
                <x-profile.stats-grid registrationDate="01/01/2024" totalTrades="50" badgeCount="3" memeCount="2" />
                
                <x-profile.settings-button label="Account Settings" sublabel="Manage your account" />
                
                <x-profile.menu-options />
            </div>
        </section>

        {{-- Test Trading Components --}}
        <section>
            <h1 class="text-2xl font-bold text-text-main mb-6">Test Trading Components</h1>
            <div class="space-y-6">
                <x-trading.price-header :price="100.50" :priceChange="['percentage' => 5.2]" ticker="TEST" />
                
                 @php
                    $mockMemeSimple = new \stdClass();
                    $mockMemeSimple->title = "Test Meme";
                    $mockMemeSimple->category = (object)['name' => 'Funny'];
                    $mockMemeSimple->image_path = 'test.jpg';
                    $mockMemeSimple->creator_id = 1;
                    $mockMemeSimple->text_alt = "Alt text";
                @endphp
                <x-trading.chart-toggle :meme="$mockMemeSimple" />
            </div>
        </section>

        {{-- Test Navigation Components --}}
        <section>
            <h1 class="text-2xl font-bold text-text-main mb-6">Test Navigation Components</h1>
            <div class="relative h-16 bg-surface-100 overflow-hidden rounded-lg">
                <x-navigation.ticker :memes="[
                    ['id' => 1, 'ticker' => 'TEST', 'change' => 10], 
                    ['id' => 2, 'ticker' => 'DOGE', 'change' => -5]
                ]" />
            </div>
        </section>

        {{-- Test Admin Modals --}}
        <section>
            <h1 class="text-2xl font-bold text-text-main mb-6">Test Admin Modals</h1>
            <div class="flex gap-4">
                <x-forms.button onclick="showModal('editEventModal')">Edit Event Modal</x-forms.button>
                <x-forms.button onclick="showModal('moderation-modal')">Moderation Modal</x-forms.button>
            </div>
            
            <x-admin.event-modal />
            <x-admin.moderation-modal />
        </section>

    </main>
</x-base>
