"use client";
import { Header } from "../common/Header";
import { Footer } from "../common/Footer";
import navigationData from "../../data/navigationData";

export default function Layout({ children }) {
    return (
        <>
            <Header navigation={navigationData} />
            {children}
            <Footer />
        </>
    )

}