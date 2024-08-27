"use client";
import Layout from "../../components/layouts/Layout";
import FormLoginComponent from "../../components/auth/FormLogin";

export const LoginPage = () => {
    return (
        <>
            <Layout>
                <FormLoginComponent />
            </Layout>
        </>
    )
}

export default LoginPage;