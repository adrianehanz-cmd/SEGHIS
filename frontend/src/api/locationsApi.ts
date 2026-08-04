import axiosClient from "../infrastructure/axios/axiosClient";

export type LocationOption = { code: string; name: string };
const get = async (path: string, params = {}) => (await axiosClient.get(path, { params })).data.data as LocationOption[];

export const getRegions = () => get("/locations/regions");
export const getProvinces = (region: string) => get("/locations/provinces", { region });
export const getMunicipalities = (region: string, province: string) => get("/locations/municipalities", { region, province });
export const getBarangays = (municipality: string) => get("/locations/barangays", { municipality });
