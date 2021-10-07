import React from 'react'
import {Switch, Route, Redirect} from 'react-router-dom';
import {AuthPage} from './pages/AuthPage'
import {RegisterPage} from './pages/RegisterPage'
import { ForgotPassword } from './pages/ForgotPassword';
import { ResetPassword } from './pages/ResetPassword';
import { HomePage } from './pages/HomePage';

export const useRoutes = isAuth => {
    if(isAuth) {
        return (
            <Switch>
                <Route path="/home/:page?/:category?/:SearchField?" exact>
                    <HomePage />
                </Route>
                <Redirect to="/home" />
            </Switch>
        );
    }

    return(
        <Switch>
            <Route path="/" exact>
                <AuthPage />
            </Route>
            <Route path="/register" exact>
                <RegisterPage />
            </Route>
            <Route path="/forgotPassword" exact>
                <ForgotPassword />
            </Route>
            <Route path="/resetPassword/:token/:id" exact>
                <ResetPassword />
            </Route>
            <Redirect to="/" />
        </Switch>
    );
};