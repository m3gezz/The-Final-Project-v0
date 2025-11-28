import React from "react";
import { Outlet } from "react-router-dom";
import Header from "../slices/user/Header";

export default function UserLayout() {
  return (
    <>
      <Header />
      <Outlet />
    </>
  );
}
