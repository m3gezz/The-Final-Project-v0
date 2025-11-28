import React from "react";
import { createBrowserRouter, Navigate } from "react-router-dom";
import NotFound from "../pages/other/NotFound";
import GuestLayout from "../layouts/GuestLayout";
import UserLayout from "../layouts/UserLayout";
import AdminLayout from "../layouts/AdminLayout";
import Dashboard from "../pages/admin/Dashboard";
import Home from "../pages/user/Home";
import SignIn from "../pages/guest/SignIn";
import SignUp from "../pages/guest/SignUp";

const routes = [
  {
    path: "/",
    element: <Navigate to={"/guest"} />,
  },
  {
    path: "/guest",
    element: <GuestLayout />,
    children: [
      {
        path: "/guest",
        element: <SignIn />,
      },
      {
        path: "/guest/sign-up",
        element: <SignUp />,
      },
    ],
  },
  {
    path: "/user",
    element: <UserLayout />,
    children: [
      {
        path: "/user",
        element: <Home />,
      },
    ],
  },
  {
    path: "/admin",
    element: <AdminLayout />,
    children: [
      {
        path: "/admin",
        element: <Dashboard />,
      },
    ],
  },
  {
    path: "*",
    element: <NotFound />,
  },
];

export const router = createBrowserRouter(routes);
