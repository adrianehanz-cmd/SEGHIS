import axiosClient from "../infrastructure/axios/axiosClient";
export type Ward = { ward_nr?: string; ward_id?: string; id?: string; description?: string; name?: string; deptid?: string; department_name?: string | null };
export const getWards = async () => (await axiosClient.get("/seghis/ward/show")).data.data as Ward[];
