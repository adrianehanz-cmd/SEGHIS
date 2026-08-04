import { useQuery } from "@tanstack/react-query";
import { Bell } from "lucide-react";
import { getNotifications } from "../../api/notificationsApi";

export default function NotificationsPage() {
  const { data = [], isPending } = useQuery({ queryKey: ["notifications"], queryFn: getNotifications });
  return <div className="mx-auto max-w-4xl"><p className="text-sm font-bold uppercase tracking-[.16em] text-teal-700">Activity</p><h2 className="mt-1 text-3xl font-bold tracking-tight">Notifications</h2><div className="mt-7 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">{isPending && <p className="p-8 text-slate-500">Loading notifications…</p>}{!isPending && data.length === 0 && <p className="p-8 text-slate-500">No notifications yet.</p>}{data.map((notification) => <article key={notification.id} className="flex gap-4 border-b border-slate-100 p-5 last:border-0"><div className="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-teal-50 text-teal-700"><Bell size={18} /></div><div><p className="font-bold text-slate-800">{notification.title}</p><p className="mt-1 text-sm text-slate-600">{notification.message}</p><p className="mt-2 text-xs text-slate-400">{new Date(notification.created_at).toLocaleString()}</p></div></article>)}</div></div>;
}
