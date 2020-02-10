import React from "react";
import {
  BrowserRouter as Router,
  Switch,
  Route,
  Link
} from "react-router-dom";
import Nav from "./components/global/Nav";

import Home from "./pages/home/Home";
import Biglist from "./components/lists/biglist/Biglist";
import TeamMaster from "./pages/teams/TeamMaster";
import TeamDetail from "./pages/teams/TeamDetail";



export default function App() {
  return (
      <Router>
        <div>
          <Nav>

            {/* A <Switch> looks through its children <Route>s and
            renders the first one that matches the current URL. */}
            <Switch>

              <Route path={"/t/:id"} component={TeamDetail}>
              </Route>
              <Route path="/t">
              <TeamMaster />
              </Route>

              <Route path="/">
                <Home/>
              </Route>
            </Switch>
          </Nav>


        </div>
      </Router>
  );
}
