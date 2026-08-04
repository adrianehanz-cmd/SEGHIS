import axiosClient from "../infrastructure/axios/axiosClient";
export const getAppointments=async()=> (await axiosClient.get("/appointments")).data.data;
export const searchAppointmentPatients=async(search:string)=> (await axiosClient.get("/appointments/patients",{params:{search}})).data.data;
export const createAppointment=async(data:Record<string,string>)=> (await axiosClient.post("/appointments",data)).data.data;
export const updateAppointment=async(id:number,data:Record<string,string>)=> (await axiosClient.patch("/appointments",data,{params:{id}})).data.data;
export const deleteAppointment=async(id:number)=> (await axiosClient.delete("/appointments",{params:{id}})).data.data;
