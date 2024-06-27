<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Your account is not yet activated. Please contact the admin to activate your account.') }}
    </div>

    <div class="mt-4 flex items-center justify-between">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"
           class="underline text-sm text-gray-600 hover:text-gray-900">
            {{ __('Log Out') }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>

    <div class="mt-4 flex items-center justify-center">
        <a href="https://api.whatsapp.com/send?phone=6285730221383&text=Please%20activate%20my%20account."
           class="flex items-center bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700">
            <i class="fab fa-whatsapp mr-2"></i> {{ __('Request Activation via WhatsApp') }}
        </a>
    </div>
</x-guest-layout>
