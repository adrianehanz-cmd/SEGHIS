import axiosClient from "../infrastructure/axios/axiosClient";

export type Patient = {
  pid: string; dateRegistered?: string | null; name_last: string; name_first: string; name_middle?: string | null;
  date_birth?: string | null; age?: string | number | null; sex?: string | null; civil_status?: string | null;
  place_birth?: string | null; Street1?: string | null; Barangay?: string | null; City?: string | null;
  Province?: string | null; Country?: string | null; ZipCode?: string | null; ethnic?: string | null;
  religion?: string | null; MotherOfPatient?: string | null; FatherOfPatient?: string | null;
  SpouseOfPatient?: string | null; deathdate?: string | null; brgy_code?: string | null; brgy_code_10?: string | null;
  municity_code?: string | null; municity_code_10?: string | null; province_code?: string | null;
  province_code_10?: string | null; region_code?: string | null; region_code_10?: string | null; source?: "local" | "seghis";
};

export type PatientPage = { items: Patient[]; pagination: { page: number; per_page: number; total: number; total_pages: number }; sources: { local: number; seghis: number } };
export const getPatients = async (page = 1, search = "") => (await axiosClient.get("/patients", { params: { page, per_page: 10, search } })).data.data as PatientPage;
export const createPatient = async (patient: Record<string, unknown>) => (await axiosClient.post("/patients", patient)).data.data as Patient;
export const updatePatient = async (pid: string, patient: Record<string, unknown>) => (await axiosClient.patch("/patients", patient, { params: { pid } })).data.data as Patient;
export const deletePatient = async (pid: string) => axiosClient.delete("/patients", { params: { pid } });
