import http from "../http-common";

class TodoDataService{
    getTodoList(userId){
        return http.get(`/todo/task?user=${userId}`);
    }

    login(data){
        return http.post("/login",data);
    }

}