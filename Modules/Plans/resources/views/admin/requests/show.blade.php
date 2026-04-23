<x-company::layouts.master>
    <x-slot name="header">
        Review Request: {{ $request->company->name }}
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Request Detail Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8">
                <div class="flex justify-between items-start mb-8">
                    <div class="flex items-center">
                        <div class="w-14 h-14 bg-blue-50 text-[#4169E1] rounded-2xl flex items-center justify-center font-bold text-xl mr-5 border border-blue-100 uppercase">
                            {{ substr($request->company->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900">{{ $request->company->name }}</h3>
                            <p class="text-slate-500">Subscription Request #{{ $request->id }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div class="space-y-4">
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Requested Plan</p>
                            <p class="text-lg font-bold text-[#4169E1]">{{ $request->plan->name }}</p>
                            <p class="text-xs text-slate-500">${{ number_format($request->plan->price, 2) }} / {{ $request->plan->billing_cycle }}</p>
                        </div>
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Request Type</p>
                            <p class="text-lg font-bold text-slate-800 capitalize">{{ $request->type }}</p>
                        </div>
                    </div>

                    <div class="bg-blue-50/30 p-5 rounded-2xl border border-blue-100">
                        <p class="text-[10px] text-[#4169E1] font-bold uppercase tracking-widest mb-3">Company Notes</p>
                        <p class="text-sm text-slate-600 italic leading-relaxed">
                            {{ $request->user_notes ?? 'No notes provided by company admin.' }}
                        </p>
                    </div>
                </div>

                @if($request->status === 'pending')
                    <div class="border-t border-gray-50 pt-8 mt-8">
                        <h4 class="text-lg font-bold text-slate-900 mb-6">Processing Action</h4>
                        <form action="{{ route('admin.requests.update', $request) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-6">
                                <label for="admin_notes" class="block text-sm font-bold text-slate-700 mb-2">Internal Administration Notes</label>
                                <textarea name="admin_notes" id="admin_notes" rows="4" placeholder="e.g. payment verified via bank transfer..." class="w-full rounded-2xl border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] transition-all"></textarea>
                            </div>

                            <div class="flex space-x-4">
                                <button type="submit" name="status" value="rejected" class="flex-1 py-4 bg-white border-2 border-red-100 text-red-600 font-bold rounded-2xl hover:bg-red-50 hover:border-red-200 transition-all text-center">
                                    Reject Request
                                </button>
                                <button type="submit" name="status" value="approved" class="flex-2 py-4 bg-[#4169E1] text-white font-bold rounded-2xl hover:bg-blue-800 shadow-xl shadow-blue-500/20 transition-all text-center px-12">
                                    Approved & Activate Subscription
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="border-t border-gray-50 pt-8 mt-8">
                        <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 italic">
                            <p class="text-sm font-bold text-slate-500 mb-2 uppercase">Admin Decision Notes</p>
                            <p class="text-slate-600">{{ $request->admin_notes ?? 'No administration notes recorded.' }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-company::layouts.master>
