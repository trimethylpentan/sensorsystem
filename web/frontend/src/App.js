import React from 'react';
import './App.css';
import Container from "./components/Container";

class App extends React.Component {
    render() {
        return (
            <div className="App">
                <header className="App-header">
                    <Container/>
                </header>
            </div>
        );
    }
}

export default App;
