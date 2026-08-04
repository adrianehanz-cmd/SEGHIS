import { useState } from "react";
import type { FormEvent } from "react";
import { useNavigate } from "react-router-dom";
import { ArrowRight, BadgeCheck, Eye, EyeOff, KeyRound, LockKeyhole, ShieldCheck, UserRound } from "lucide-react";
import { useAuthStore } from "../../application/stores/authStore";
import AuthService from "../../services/AuthService";
import { registerApi } from "../../api/authApi";
import Logo from "../components/Logo";

const inputClass = "w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10";

function PasswordField({ value, onChange, placeholder, confirm = false, showPassword, onToggleVisibility }: { value: string; onChange: (value: string) => void; placeholder: string; confirm?: boolean; showPassword: boolean; onToggleVisibility: () => void }) {
  return <div>
    <label className="mb-1.5 block text-sm font-semibold text-slate-700">{confirm ? "Confirm password" : "Password"}</label>
    <div className="relative">
      <LockKeyhole className="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
      <input required minLength={8} type={showPassword ? "text" : "password"} value={value} onChange={(event) => onChange(event.target.value)} placeholder={placeholder} className={`${inputClass} pr-12`} />
      <button type="button" onClick={onToggleVisibility} aria-label={showPassword ? "Hide password" : "Show password"} className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700">
        {showPassword ? <EyeOff size={18} /> : <Eye size={18} />}
      </button>
    </div>
  </div>;
}

export default function LoginPage() {
  const navigate = useNavigate();
  const login = useAuthStore((state) => state.login);
  const [create, setCreate] = useState(false);
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [pid, setPid] = useState("");
  const [loginId, setLoginId] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [message, setMessage] = useState("");
  const [error, setError] = useState("");
  const [busy, setBusy] = useState(false);
  const [showPassword, setShowPassword] = useState(false);

  const switchPanel = (isCreate: boolean) => {
    setCreate(isCreate); setMessage(""); setError(""); setPassword(""); setConfirmPassword("");
  };
  const togglePasswordVisibility = () => setShowPassword((shown) => !shown);

  const signIn = async (event: FormEvent) => {
    event.preventDefault(); setBusy(true); setError("");
    try {
      const response = await AuthService.login(username, password);
      login(response.data.token, response.data.user); navigate("/dashboard");
    } catch { setError("Your Login ID or password is incorrect."); }
    finally { setBusy(false); }
  };
  const register = async (event: FormEvent) => {
    event.preventDefault(); setError("");
    if (password !== confirmPassword) { setError("Passwords do not match."); return; }
    setBusy(true);
    try {
      await registerApi({ pid, login_id: loginId, password });
      setUsername(loginId); setPid(""); setLoginId(""); switchPanel(false);
      setMessage("Account created. Sign in using your Login ID and password.");
    } catch (requestError: any) { setError(requestError?.response?.data?.message ?? "Unable to create your account."); }
    finally { setBusy(false); }
  };

  return <div className="grid min-h-screen bg-slate-50 lg:grid-cols-[1.05fr_.95fr]">
    <section className="relative hidden overflow-hidden bg-slate-950 px-10 py-12 text-white lg:flex lg:flex-col">
      <div className="absolute -left-32 top-24 h-80 w-80 rounded-full bg-teal-400/15 blur-3xl" />
      <div className="relative"><Logo /></div>
      <div className="relative my-auto max-w-lg"><p className="mb-5 text-sm font-bold uppercase tracking-[.18em] text-teal-300">SegHIS workspace</p><h1 className="text-5xl font-bold leading-tight tracking-tight">Care starts with a connected team.</h1><p className="mt-6 max-w-md text-lg leading-8 text-slate-300">One secure workspace for the people who keep your hospital moving.</p><div className="mt-12 space-y-4"><div className="flex gap-4 rounded-2xl border border-white/10 bg-white/5 p-4"><div className="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-teal-300/15 text-teal-300"><ShieldCheck size={20} /></div><div><p className="font-semibold">Role-based access</p><p className="mt-1 text-sm text-slate-400">Your workspace is set from your verified staff record.</p></div></div><div className="flex gap-4 rounded-2xl border border-white/10 bg-white/5 p-4"><div className="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-teal-300/15 text-teal-300"><BadgeCheck size={20} /></div><div><p className="font-semibold">Verified staff accounts</p><p className="mt-1 text-sm text-slate-400">Only registered doctors and nurses can create an account.</p></div></div></div></div>
    </section>
    <section className="flex items-center justify-center px-5 py-10 sm:p-10"><div className="w-full max-w-md"><div className="lg:hidden"><Logo dark /></div><div className="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/50 sm:p-8"><div className="flex rounded-xl bg-slate-100 p-1"><button type="button" onClick={() => switchPanel(false)} className={`flex-1 rounded-lg px-3 py-2.5 text-sm font-bold ${!create ? "bg-white text-slate-900 shadow-sm" : "text-slate-500"}`}>Sign in</button><button type="button" onClick={() => switchPanel(true)} className={`flex-1 rounded-lg px-3 py-2.5 text-sm font-bold ${create ? "bg-white text-slate-900 shadow-sm" : "text-slate-500"}`}>Create account</button></div>{message && <div className="mt-5 rounded-xl border border-teal-100 bg-teal-50 p-3 text-sm font-medium text-teal-800">{message}</div>}{error && <div role="alert" className="mt-5 rounded-xl border border-rose-100 bg-rose-50 p-3 text-sm font-medium text-rose-700">{error}</div>}
      {!create ? <form onSubmit={signIn} className="mt-7 space-y-5"><div><p className="text-sm font-bold uppercase tracking-[.15em] text-teal-700">Welcome back</p><h2 className="mt-2 text-3xl font-bold tracking-tight">Sign in to SegHIS</h2></div><div><label className="mb-1.5 block text-sm font-semibold">Login ID</label><div className="relative"><UserRound className="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" size={18} /><input required autoComplete="username" value={username} onChange={(event) => setUsername(event.target.value)} placeholder="Enter your Login ID" className={inputClass} /></div></div><PasswordField value={password} onChange={setPassword} placeholder="Enter your password" showPassword={showPassword} onToggleVisibility={togglePasswordVisibility} /><button disabled={busy} className="flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 p-3.5 font-bold text-white disabled:opacity-60">{busy ? "Signing in…" : <>Sign in <ArrowRight size={18} /></>}</button></form> : <form onSubmit={register} className="mt-7 space-y-5"><div><p className="text-sm font-bold uppercase tracking-[.15em] text-teal-700">Staff activation</p><h2 className="mt-2 text-3xl font-bold tracking-tight">Create your account</h2><p className="mt-2 text-sm leading-6 text-slate-500">We’ll match your details with the doctor or nurse registry and assign your role automatically.</p></div><div><label className="mb-1.5 block text-sm font-semibold">PID / Personnel Number</label><div className="relative"><BadgeCheck className="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" size={18} /><input required value={pid} onChange={(event) => setPid(event.target.value)} placeholder="Doctor PID or nurse personnel number" className={inputClass} /></div><p className="mt-1.5 text-xs text-slate-500">Doctors use their PID. Nurses use their registered personnel number.</p></div><div><label className="mb-1.5 block text-sm font-semibold">Login ID</label><div className="relative"><KeyRound className="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" size={18} /><input required autoComplete="username" value={loginId} onChange={(event) => setLoginId(event.target.value)} placeholder="Login ID on your staff record" className={inputClass} /></div></div><PasswordField value={password} onChange={setPassword} placeholder="Create a password (8+ characters)" showPassword={showPassword} onToggleVisibility={togglePasswordVisibility} /><PasswordField confirm value={confirmPassword} onChange={setConfirmPassword} placeholder="Re-enter your password" showPassword={showPassword} onToggleVisibility={togglePasswordVisibility} /><button disabled={busy} className="flex w-full items-center justify-center gap-2 rounded-xl bg-teal-600 p-3.5 font-bold text-white disabled:opacity-60">{busy ? "Creating account…" : <>Create account <ArrowRight size={18} /></>}</button></form>}</div></div></section>
  </div>;
}
