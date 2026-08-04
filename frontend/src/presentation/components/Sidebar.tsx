import { Building2, CalendarDays, ChevronsLeft, ChevronsRight, ClipboardPlus, LayoutDashboard, LogOut, Stethoscope, UserRoundCog, Users } from "lucide-react";
import { NavLink, useNavigate } from "react-router-dom";
import { useAuthStore } from "../../application/stores/authStore";
import Logo from "./Logo";

const menus = [
  { title: "Overview", icon: LayoutDashboard, path: "/dashboard" },
  { title: "Patients", icon: Users, path: "/patients" },
  { title: "Appointments", icon: CalendarDays, path: "/appointments" },
  { title: "Departments", icon: Building2, path: "/departments" },
  { title: "Doctors", icon: Stethoscope, path: "/doctors" },
  { title: "Nurses", icon: UserRoundCog, path: "/nurses" },
];

export default function Sidebar({ collapsed, onToggle }: { collapsed: boolean; onToggle: () => void }) {
  const navigate = useNavigate(); const logout = useAuthStore((state) => state.logout); const user = useAuthStore((state) => state.user);
  const role = user?.role ?? (user?.role_id === 2 ? "doctor" : user?.role_id === 3 ? "nurse" : "administrator");
  const roleMenus = role === "doctor"
    ? [menus[0], { title: "Encounter", icon: ClipboardPlus, path: "/encounter" }, menus[2]]
    : role === "nurse"
      ? [menus[0], menus[1], { title: "Encounter", icon: ClipboardPlus, path: "/encounter" }, menus[2]]
      : menus;
  const signOut = () => { logout(); navigate("/login"); };
  return <aside className={`hidden shrink-0 flex-col bg-slate-950 px-4 py-6 text-white transition-all duration-200 lg:flex ${collapsed ? "w-20" : "w-72"}`}>
    <div className={`flex items-center ${collapsed ? "flex-col gap-5" : "justify-between px-3"}`}>{collapsed ? <Logo compact /> : <Logo />}<button onClick={onToggle} title={collapsed ? "Expand sidebar" : "Collapse sidebar"} className="rounded-lg p-2 text-slate-400 hover:bg-slate-800 hover:text-white">{collapsed ? <ChevronsRight size={18} /> : <ChevronsLeft size={18} />}</button></div>
    {!collapsed && <p className="mb-3 mt-10 px-3 text-[11px] font-bold uppercase tracking-[0.16em] text-slate-500">Workspace</p>}
    <nav className={`space-y-1 ${collapsed ? "mt-9" : ""}`}>{roleMenus.map(({ title, icon: Icon, path }) => <NavLink key={title} title={collapsed ? title : undefined} to={path} className={({ isActive }) => `flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition ${isActive ? "bg-teal-400 text-slate-950 shadow-lg shadow-teal-950/20" : "text-slate-300 hover:bg-slate-800 hover:text-white"}`}><Icon size={19} />{!collapsed && title}</NavLink>)}</nav>
    <div className="mt-auto border-t border-slate-800 pt-4"><button onClick={signOut} title={collapsed ? "Sign out" : undefined} className="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-300 hover:bg-rose-500/15 hover:text-rose-300"><LogOut size={19} />{!collapsed && "Sign out"}</button></div>
  </aside>;
}
