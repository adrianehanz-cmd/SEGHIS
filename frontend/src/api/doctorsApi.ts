import axiosClient from "../infrastructure/axios/axiosClient";
export type Doctor = { personnel_nr: string; pid?: string | null; dateRegistered?: string | null; name_last: string; name_first: string; name_middle?: string | null; City?: string | null; Province?: string | null; name_formal?: string | null; deptid?: string | null; license_nr?: string | null; login_id?: string | null; source?: "local" | "seghis" };
export type DoctorsPage = { items: Doctor[]; pagination: { page: number; total: number; total_pages: number }; sources: { local: number; seghis: number } };
export const getDoctors = async (page = 1, search = "") => (await axiosClient.get("/doctors", { params: { page, per_page: 10, search } })).data.data as DoctorsPage;
export const createDoctor = async (doctor: Record<string, string>) => (await axiosClient.post("/doctors", doctor)).data.data;
export type Department = { deptid?: string; dept_id?: string; id?: string; name_formal?: string; name?: string; name_short?: string; department_name?: string; description?: string; location_nr?: string | number };
export const getDepartments = async () => {
  const payload = (await axiosClient.get("/seghis/departments/show")).data.data;
  return (Array.isArray(payload) ? payload : payload?.items ?? payload?.data ?? payload?.departments ?? []) as Department[];
};
