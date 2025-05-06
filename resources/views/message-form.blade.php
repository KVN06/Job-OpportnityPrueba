@extends('layouts.home')

@section('content')
<form action="{{ route('send-message') }}" method="POST">
    @csrf
    <main class="container mx-auto py-8 px-6">
        <h1 class="text-3xl font-bold text-center mb-8">Enviar Mensaje</h1>
        <section class="bg-white shadow rounded-lg p-6 max-w-lg mx-auto">
            <div class="space-y-4">
                <div>
                    <label for="sender_id" class="block text-gray-700 font-medium">Remitente (ID)</label>
                    <input type="number" id="sender_id" name="sender_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div>
                    <label for="receiver_id" class="block text-gray-700 font-medium">Destinatario (ID)</label>
                    <input type="number" id="receiver_id" name="receiver_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div>
                    <label for="content" class="block text-gray-700 font-medium">Mensaje</label>
                    <textarea id="content" name="content" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required></textarea>
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-400">Enviar</button>
            </div>
        </section>
    </main>
</form>
@endsection
