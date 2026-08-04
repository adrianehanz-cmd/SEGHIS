import { Cross } from "lucide-react";

export default function Logo({ dark = false, compact = false }: { dark?: boolean; compact?: boolean }) {
  return (
    <div className="flex items-center gap-3">
      <div className={`grid place-items-center rounded-xl bg-teal-400 text-slate-950 shadow-lg shadow-teal-950/20 ${compact ? "h-11 w-11 rounded-2xl" : "h-10 w-10"}`}>
        <Cross size={22} strokeWidth={3} />
      </div>
      {!compact && <div className={dark ? "text-slate-900" : "text-white"}>
        <p className="text-lg font-bold leading-none tracking-tight">SegHIS</p>
        <p className={dark ? "mt-1 text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-500" : "mt-1 text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-300"}>Care, connected</p>
      </div>
      }
    </div>
  );
}
