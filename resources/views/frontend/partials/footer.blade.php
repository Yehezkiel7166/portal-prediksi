<footer class="border-t border-slate-800 bg-slate-950">
    <div class="mx-auto max-w-7xl space-y-4 px-4 py-8 text-center text-sm text-slate-400">
        @if ($siteConfiguration->footerText)
            <p>{{ $siteConfiguration->footerText }}</p>
        @endif

        @if ($siteConfiguration->contactEmail || $siteConfiguration->contactPhone || $siteConfiguration->whatsappNumber)
            <address class="flex flex-wrap justify-center gap-x-5 gap-y-2 not-italic">
                @if ($siteConfiguration->contactEmail)
                    <a href="mailto:{{ $siteConfiguration->contactEmail }}" class="hover:text-amber-300">{{ $siteConfiguration->contactEmail }}</a>
                @endif
                @if ($siteConfiguration->contactPhone)
                    <span>{{ $siteConfiguration->contactPhone }}</span>
                @endif
                @if ($siteConfiguration->whatsappNumber)
                    <span>WhatsApp: {{ $siteConfiguration->whatsappNumber }}</span>
                @endif
            </address>
        @endif

        @if ($siteConfiguration->socialLinks !== [])
            <nav aria-label="Media sosial">
                <ul class="flex flex-wrap justify-center gap-4">
                    @foreach ($siteConfiguration->socialLinks as $network => $url)
                        <li><a href="{{ $url }}" rel="noopener noreferrer" class="capitalize hover:text-amber-300">{{ $network }}</a></li>
                    @endforeach
                </ul>
            </nav>
        @endif

        <p>&copy; {{ now()->year }} {{ $siteConfiguration->siteName }}. Seluruh hak dilindungi.</p>
    </div>
</footer>
