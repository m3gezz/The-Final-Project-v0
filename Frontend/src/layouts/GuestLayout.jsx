import React from "react";
import { Outlet } from "react-router-dom";
import Header from "../slices/guest/Header";

export default function GuestLayout() {
  return (
    <>
      <Header />
      <Outlet />
    </>
  );
}
