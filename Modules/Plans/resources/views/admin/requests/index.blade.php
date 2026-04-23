<x-company::layouts.master>
    <x-slot name="header">
        Subscription Requests
    </x-slot>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8">
            <h3 class="text-xl font-bold text-slate-800 mb-6">Manage Incoming Requests</h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Company</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Requested Plan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Type</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Date</th>
                            <th class="px-6 py-4 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($requests as $req)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#4169E1] flex items-center justify-center font-bold text-xs mr-3 border border-blue-100 uppercase">
                                        {{ substr($req->company->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-slate-800">{{ $req->company->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-[#4169E1]">{{ $req->plan->name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg uppercase">
                                    {{ $req->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 text-[10px] font-bold rounded-full uppercase
                                    @if($req->status == 'pending') bg-amber-100 text-amber-600 border border-amber-200
                                    @elseif($req->status == 'approved') bg-green-100 text-green-600 border border-green-200
                                    @else bg-red-100 text-red-600 border border-red-200 @endif">
                                    {{ $req->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $req->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('admin.requests.show', $req) }}" class="text-[#4169E1] hover:text-blue-900 font-bold border-b border-[#4169E1]/0 hover:border-[#4169E1] transition-all pb-0.5">
                                    Review Details
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <p class="text-slate-400 font-medium">No subscription requests found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</x-company::layouts.master>
