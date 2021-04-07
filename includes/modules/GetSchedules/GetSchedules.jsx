// External Dependencies
import React, { Component } from 'react';

// Internal Dependencies
import './style.css';


class GetSchedules extends Component {

  static slug = 'get_schedules';

  render() {
    // const Content = this.props.content;
    const button_type = this.props.button_type;
 
    if (button_type === "uber") {
      return (
        <div class="uber-ride-container">
					<div class="uber-ride-inner">
						<a href="https://m.uber.com/">
							<div class="btn-description-container">
								<div class="ride-title">
									<div>Get a ride</div>
								</div>
								<div class="btn-description"> 
									<div>3 MIN AWAY</div>
									<div>$8-10 on UberX</div>
								</div>
							</div>
						</a>
					</div>
				</div>
      );  
    }
    return (
      <div class="lyft-ride-container">
        <div class="lyft-ride-inner">
          <a href="https://lyft.com">
            <div class="btn-description-container">
              <div class="lyft-ride-title">
                <div>Get a ride</div>
                <div class="lyft-btn-description-1">Lyft in 4min</div>
              </div>
              <div class="lyft-btn-description-2"> 
                <div>$8-10</div>
              </div>
            </div>
          </a>
        </div>
      </div>
    );
  }
}

export default GetSchedules;
