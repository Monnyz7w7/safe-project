@props(['steps'])

<div class="bg-white py-12 px-8 shadow-sm rounded">
    <div class="mx-auto grid max-w-2xl grid-cols-1 gap-8 overflow-hidden lg:mx-0 lg:max-w-none lg:grid-cols-3">
        @foreach ($steps as $key => $step)
            <div>
                <div class="flex items-center text-sm font-semibold leading-6 text-indigo-600">
                    <svg viewBox="0 0 4 4" class="mr-4 h-1 w-1 flex-none" aria-hidden="true">
                        <circle cx="2" cy="2" r="2" fill="currentColor" />
                    </svg>
                    Step {{ $key + 1 }}
                    <div class="absolute -ml-2 h-px w-screen -translate-x-full bg-gray-900/10 sm:-ml-4 lg:static lg:-mr-6 lg:ml-8 lg:w-auto lg:flex-auto lg:translate-x-0" aria-hidden="true">
                    </div>
                </div>
                <p class="mt-6 text-base font-semibold leading-8 tracking-tight text-gray-900">
                    {{ $step['title'] }}
                </p>
                <p class="mt-1 text-base leading-7 text-gray-600">
                    {!! $step['body'] !!}
                </p>
            </div>
        @endforeach
    </div>
</div>
