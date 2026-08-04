import axiosClient from "../infrastructure/axios/axiosClient";
export type Notification = { id: number; type: string; title: string; message: string; is_read: boolean | number; created_at: string };
export const getNotifications = async () => (await axiosClient.get("/notifications")).data.data as Notification[];
export const markNotificationsRead = async () => axiosClient.patch("/notifications/read");
