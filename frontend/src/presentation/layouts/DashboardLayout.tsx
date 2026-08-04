import { useState } from "react";
import { Outlet } from "react-router-dom";
import Sidebar from "../components/Sidebar";
import Topbar from "../components/Topbar";

export default function DashboardLayout() {
    const [collapsed, setCollapsed] = useState(false);

    return (

        <div className="flex h-screen bg-slate-100">

            <Sidebar collapsed={collapsed} onToggle={() => setCollapsed((value) => !value)} />

            <div className="flex flex-col flex-1">

                <Topbar />

                <main className="flex-1 overflow-y-auto p-6">

                    <Outlet />

                </main>

            </div>

        </div>

    );

}
