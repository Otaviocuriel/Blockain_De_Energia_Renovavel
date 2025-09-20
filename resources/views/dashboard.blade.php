<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-emerald-600 leading-tight flex items-center gap-2">
            <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12 flex justify-end">
        <div class="max-w-5xl w-full sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col items-center">
                    <div class="text-4xl font-bold text-emerald-500 mb-2">{{ auth()->user()->name }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-300 mb-4">Bem-vindo ao seu painel!</div>
                    <div class="flex gap-2">
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs">Usuário</span>
                        <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs">Blockchain Verde</span>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col items-center">
                    <div class="text-2xl font-bold text-indigo-500 mb-2">Transações</div>
                    <div class="text-4xl font-bold text-indigo-600 mb-2">12</div>
                    <div class="text-sm text-gray-500 dark:text-gray-300">Total de transações realizadas</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col items-center">
                    <div class="text-2xl font-bold text-yellow-500 mb-2">Impacto Ambiental</div>
                    <div class="text-4xl font-bold text-yellow-600 mb-2">+ 2.5 Ton CO₂</div>
                    <div class="text-sm text-gray-500 dark:text-gray-300">Redução de carbono comprovada</div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Resumo das Atividades</h3>
                <ul class="space-y-3">
                    <li class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                        Compra de energia renovável realizada em 18/09/2025
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-indigo-400"></span>
                        Venda de créditos de carbono em 15/09/2025
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                        Certificação de energia limpa recebida
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
