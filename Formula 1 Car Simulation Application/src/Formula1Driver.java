
public class Formula1Driver extends Driver{
	
	private int firstPositions = 0;
	private int secondPositions = 0;
	private int thirdPositions = 0;
	private int points = 0;
	private int numOfRaces;
	
	//Constructor
	public Formula1Driver(String name, String location, String team) {
		super(name, location, team);
	}
	public Formula1Driver() {super();}
	
	
	//Save points
	@Override
	public void calculatePoints(boolean isFinished, int position) {
		
		if(isFinished) {
			if (position == 1) {
				points += 25;
				firstPositions += 1;
			} else if (position == 2) {
				points += 18;
				secondPositions += 1;
			} else if (position == 3) {
				points += 15;
				thirdPositions += 1;
			} else if (position == 4) {
				points += 12;
			} else if (position == 5) {
				points += 10;
			} else if (position == 6) {
				points += 8;
			} else if (position == 7) {
				points += 6;
			} else if (position == 8) {
				points += 4;
			} else if (position == 9) {
				points += 2;
			} else if (position == 10) {
				points += 1;
			} else {
				System.out.println("Points are awarded for first 10 positions only");
			}
		}
		else {
			System.out.println("Must finish the race to award the points");
		}	
		
	}

	//Getters and setters

	public int getFirstPositions() {
		return firstPositions;
	}


	public void setFirstPositions(int firstPositions) {
		this.firstPositions = firstPositions;
	}


	public int getSecondPositions() {
		return secondPositions;
	}


	public void setSecondPositions(int secondPositions) {
		this.secondPositions = secondPositions;
	}


	public int getThirdPositions() {
		return thirdPositions;
	}


	public void setThirdPositions(int thirdPositions) {
		this.thirdPositions = thirdPositions;
	}


	public int getPoints() {
		return points;
	}


	public void setPoints(int points) {
		this.points = points;
	}


	public int getNumOfRaces() {
		return numOfRaces;
	}


	public void setNumOfRaces(int numOfRaces) {
		this.numOfRaces = numOfRaces;
	}
	
}
