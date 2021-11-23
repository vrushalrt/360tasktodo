import logo from './logo.svg';
import React, { useState } from "react";
import './App.css';
import { Route, Link } from "react-router-dom";
import "bootstrap/dist/css/bootstrap.min.css";
// Importing Components
import Form from './components/Form';
import TodoList from "./components/TodoList";
import Login from "./components/Login";

function App() {
    const [user, setUser, inputText, setInputText] = React.useState(null);

    async function login (user=null){
        setUser(user);
    }

    async function logout(){
        setUser(null);
    }

    return (
        <div className="App">
            <nav className="navbar navbar-expand navbar-dark bg-dark">
                <a href="/todo" className="navbar-brand">
                   Todo App
                </a>
                <div className="navbar-nav mr-auto">
                    <li className="nav-item">
                        <Link to={"/todo"} className="nav-link">
                            Todo
                        </Link>
                    </li>
                    <li className="nav-item" >
                        { user ? (
                            <a onClick={logout} className="nav-link" style={{cursor:'pointer'}}>
                                Logout {user.name}
                            </a>
                        ) : (
                            <Link to={"/login"} className="nav-link">
                                Login
                            </Link>
                        )}
                    </li>
                </div>
            </nav>

            <div className="container mt-3">

                    <Route
                        path="/login"
                        // render={(props) => (
                        //     <Login {...props} login={<login/>} />
                        // )}
                        // element={<Login />}
                        component={<Login/>}
                    />

            </div>

        </div>
        // <div className="App">
        //     <header>
        //         <h1>ToDO</h1>
        //     </header>
        //     <Form/>
        //     <TodoList/>
        // </div>
      );
}

export default App;
