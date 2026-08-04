import axiosClient from "../infrastructure/axios/axiosClient";

export type MedicalRecord = {
  patient_pid: string;
  notice: string;
  medical_history: { date: string; title: string; details: string }[];
  test_results: { date: string; test: string; result: string; status: string }[];
};

export const getMedicalRecords = async (patientPid: string) =>
  (await axiosClient.get("/medical-records", { params: { patient_pid: patientPid } })).data.data as MedicalRecord;
