import api from "./api";

export default {
  getAll() {
    return api.get("/employees"); // GET /api/employees
  },
  get(id) {
    return api.get(`/employees/${id}`); // GET /api/employees/{id}
  },
  create(data) {
    return api.post("/employees", data); // POST /api/employees
  },
  update(id, data) {
    return api.put(`/employees/${id}`, data); // PUT /api/employees/{id}
  },
  delete(id) {
    return api.delete(`/employees/${id}`); // DELETE /api/employees/{id}
  },
};
