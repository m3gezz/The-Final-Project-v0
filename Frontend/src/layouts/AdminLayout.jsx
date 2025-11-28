import React from "react";
import { Outlet } from "react-router-dom";
import Header from "../slices/admin/Header";

export default function AdminLayout() {
  return (
    <>
      <Header />
      <Outlet />
    </>
  );
}
