import axiosClient from "../infrastructure/axios/axiosClient";
export type Nurse = { personnel_nr: string; name_last: string; name_first: string; name_middle?: string | null; deptid?: string | null; name_formal?: string | null; license_nr?: string | null; login_id?: string | null; source?: "local" | "seghis" };
export type NursesPage = { items: Nurse[]; pagination: { page: number; total: number; total_pages: number } };
export const getNurses = async (page = 1, search = "") => (await axiosClient.get("/nurses", { params: { page, per_page: 10, search } })).data.data as NursesPage;
export const createNurse = async (nurse: Record<string, string>) => (await axiosClient.post("/nurses", nurse)).data.data;
