import React, {Component} from 'react';

import "material-design-lite/material.min";
import "material-design-lite/material.min.css";
import "../../assets/css/materialIcons.css";
import {Link} from "react-router-dom";

export default class Nav extends Component{
    render() {
        return(
        <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
            <header class="mdl-layout__header">
                <div class="mdl-layout__header-row">
                    <span class="mdl-layout-title">FRC Admin</span>
                    <div class="mdl-layout-spacer"></div>

                </div>
            </header>
            <div class="mdl-layout__drawer">
                <span class="mdl-layout-title">Team 100</span>
                <nav class="mdl-navigation">
                    <Link class="mdl-navigation__link" to="/">Home</Link>
                    <Link class="mdl-navigation__link" to="/t">Teams</Link>
                    <Link class="mdl-navigation__link" to="/p">Pick list</Link>
                    <Link class="mdl-navigation__link" to="/m">Match list</Link>
                    <Link class="mdl-navigation__link" to="/e">Event Info</Link>




                </nav>
            </div>
            <main class="mdl-layout__content">
                <div class="page-content">{this.props.children}</div>
            </main>
        </div>);
    }
}
