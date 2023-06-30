import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileWriter;
import java.io.IOException;
import java.sql.Date;
import java.util.*;

public class Formula1ChampionshipManager implements ChampionshipManager {

	// Variables
	int numOfDrivers;
	ArrayList<Formula1Driver> drivers = new ArrayList<>();
	ArrayList<String> teams = new ArrayList<>();
	ArrayList<Races> raceData = new ArrayList<>();
	Map<String, Map> races = new HashMap<>();

	public static void main(String[] args) {

		Formula1ChampionshipManager formula1Driver = new Formula1ChampionshipManager();

		Scanner enter = new Scanner(System.in);

		label:
		//Print the menu options
		while (true) {

			System.out.println("\n-----------**** FORMULA1 RACING CHAMPIONSHIP ****------------");
			System.out.println("\n----------------------------MENU-----------------------------");
			System.out.println("\nEnter 1 to Create a new driver");
			System.out.println("Enter 2 to Delete a driver");
			System.out.println("Enter 3 to Change the driver for an existing constructor team");
			System.out.println("Enter 4 to Display driver detail");
			System.out.println("Enter 5 to Display driver table");
			System.out.println("Enter 6 to Add race results");
			System.out.println("Enter 7 to Open GUI");
			System.out.println("Enter 0 to Exit from the program");
			System.out.println("\n-------------------------------------------------------------");
			System.out.print("\nPlease enter your command : ");

			String input = enter.next().toUpperCase(Locale.ROOT);

			if ("1".equals(input)) {				// Get driver data
				System.out.print("\nEnter Driver Name : ");
				enter.nextLine();
				String name = enter.nextLine();
				System.out.print("Enter Driver Location : ");
				String location = enter.nextLine();
				System.out.print("Enter Driver Team : ");
				String team = enter.nextLine();

				formula1Driver.createNewDriver(name, location, team);
				System.out.println();
				System.out.println(name +",Driver has been successfully added");
			} else if ("2".equals(input)) {			// Get driver data
				System.out.print("\nEnter Driver Name : ");
				enter.nextLine();
				String name = enter.nextLine();

				formula1Driver.deleteDriver(name);
				System.out.println();
			} else if ("3".equals(input)) {			// Get driver data
				System.out.print("\nEnter Driver's Name : ");
				enter.nextLine();
				String name = enter.nextLine();
				System.out.print("\nEnter Driver's new Team : ");
				String team = enter.nextLine();

				formula1Driver.changeTeam(name, team);
				System.out.println();
			} else if ("4".equals(input)) {			// Get driver data
				System.out.print("\nEnter Driver's Name : ");
				enter.nextLine();
				String name = enter.nextLine();
				formula1Driver.displayStatistics(name);
				System.out.println();
			} else if ("5".equals(input)) {
				formula1Driver.displayDriverTable();
			} else if ("6".equals(input)) {
				System.out.print("Enter Date (yyyy-mm-dd) : ");
				String date = enter.next();

				Map<String, Integer> positions = new HashMap<>();

				// Get position data
				for (int i = 0; i < formula1Driver.drivers.size(); i++) {

					System.out.print("Enter Driver Name : ");
					enter.nextLine();
					String name = enter.nextLine();
					System.out.print("Enter Driver Position : ");
					int position = enter.nextInt();

					positions.put(name, position);
				}

				formula1Driver.raceData.add(new Races(date, positions));
				formula1Driver.markRaceCompleted(date, positions);
				System.out.println();
			} else if ("7".equals(input)) {
				GUI gui = new GUI(formula1Driver.drivers, formula1Driver.raceData);
			} else if ("0".equals(input)) {
				formula1Driver.saveState();
				System.out.println("\nSaving and exiting....");
				break label;
			} else {
				System.out.println("Invalid input");
			}
		}
	}

	@Override //Create a new driver object and store data
	public void createNewDriver(String name, String location, String team) {
		// Check if a driver is already in the team
		boolean hasPlayer = false;
		for (String t : teams) {
			if (t.equalsIgnoreCase(team)) {
				System.out.println("~ This team already has a player ~");
				hasPlayer = true;
				break;
			}
		}

		// Create player otherwise
		if (!hasPlayer) {
			Formula1Driver fc = new Formula1Driver(name, location, team);
			drivers.add(fc);
			numOfDrivers = drivers.size();
		}
	}

	//Delete a driver by entering the name
	@Override
	public void deleteDriver(String name) {

		// Check if player exists
		boolean hasPlayer = false;
		for (Formula1Driver driver : drivers) {
			if (driver.getName().equalsIgnoreCase(name)) {
				hasPlayer = true;
				teams.remove(driver.getTeam()); // Remove team
				drivers.remove(driver); 		// Remove driver
				System.out.println();
				System.out.println(name+",Driver has been deleted successfully");
				numOfDrivers -= 1;
				break;
			}
		}
		if (!hasPlayer) {
			System.out.println("\nDriver not found, please input the driver name as the system details");
		}

	}

	// Change Team
	@Override
	public void changeTeam(String name, String newTeam) {

		// Check for player
		boolean hasPlayer = false;
		for (Formula1Driver driver : drivers) {
			if (driver.getName().equalsIgnoreCase(name)) {
				hasPlayer = true;
				driver.setTeam(newTeam);
				System.out.println("\nDriver's team has been changed successfully");
			}
		}
		if (!hasPlayer) {
			System.out.println("\nDriver not found, please input the driver name as the system details");
		}

	}

	@Override
	//display driver statics
	public void displayStatistics(String name) {
		for (Formula1Driver driver : drivers) {
			if (driver.getName().equalsIgnoreCase(name)) {
				System.out.println("Points           : " + driver.getPoints());
				System.out.println("1st positions  	 : " + driver.getFirstPositions());
				System.out.println("2nd positions 	 : " + driver.getSecondPositions());
				System.out.println("3rd positions  	 : " + driver.getThirdPositions());
			}
		}
	}

	@Override
	public void displayDriverTable() {

		Formula1Driver driver[] = new Formula1Driver[drivers.size()];

		// To array
		for (int i = 0; i < drivers.size(); i++) {
			driver[i] = drivers.get(i);
		}

		// Sort according to descending order
		for (int i = 0; i < drivers.size(); i++) {
			for (int j = i + 1; j < drivers.size(); j++) {
				Formula1Driver tmp;

				if (driver[i].getPoints() < driver[j].getPoints()) {
					tmp = driver[i];
					driver[i] = driver[j];
					driver[j] = tmp;
				}

				// If 2 drivers have same points
				else if (driver[i].getPoints() == driver[j].getPoints()) {
					if (driver[i].getFirstPositions() > driver[j].getFirstPositions()) {
						tmp = driver[j];
						driver[j] = driver[i];
						driver[i] = tmp;
					} else {
						tmp = driver[i];
						driver[i] = driver[j];
						driver[j] = tmp;
					}
				}
			}
		}

		//Display table
		System.out.print("+----------+-------------+-------------+-------------+");
		System.out.print("\n| Position |");
		System.out.print(" Driver Name |");
		System.out.print(" Driver Team |");
		System.out.println("   Points    |");
		System.out.print("+----------+-------------+-------------+-------------+");
		int position = 1;
		for (int i = 0; i < drivers.size(); i++) {
			System.out.format("\n|%5s     |", (position++));
			System.out.format("%9s    |", driver[i].getName());
			System.out.format("%11s  |" , driver[i].getTeam());
			System.out.format("%10s   |" , driver[i].getPoints());
		}
		System.out.println("\n+----------+-------------+-------------+-------------+");
	}

	//Mark race completed
	@Override
	public void markRaceCompleted(String date, Map<String, Integer> positions) {

		for (Map.Entry<String, Integer> stringIntegerEntry : positions.entrySet()) {
			Map.Entry pair = (Map.Entry) stringIntegerEntry;

			// Update statistics
			for (Formula1Driver driver : drivers) {
				if (driver.getName().equals(pair.getKey())) {
					driver.calculatePoints(true, (int) pair.getValue()); // Set positions
				}
			}
		}
	}

	//Save to file
	@Override
	public void saveState() {

		// Write driver data to file
		try {
			FileWriter file = new FileWriter("driver.txt");
			
			//Data
			for (Formula1Driver driver : drivers) {
				file.write( "\nDriver Name     : " + driver.getName() + "\nDriver Location : " + driver.getLocation() + "\nDriver Team     : " + driver.getTeam() + "\nPoints          : " + driver.getPoints() + "\n1st Places      : "
						+ driver.getFirstPositions() + "\n2st Places      : " + driver.getSecondPositions() + "\n3st Places      : " + driver.getThirdPositions() + "\n-------------------------------");
			}

			file.close();
			System.out.println("\nSuccessfully wrote driver data to the file");
		} catch (IOException e) {
			System.out.println("\nAn error occurred");
			e.printStackTrace();
		}

		// Write race data
		try {
			FileWriter file = new FileWriter("race.txt");

			int x = 0;
			for (int i = 0; i < raceData.size(); i++) {
				String date = raceData.get(i).getDate();
				Map<String, Integer> mp = raceData.get(i).getPositions();

				//Data
				for (String driver : mp.keySet()) {
					file.write("\nDate         : " + date + "\nDriver Name  : " + driver + "\nPosition     : " + mp.get(driver) + "\n-------------------------");
					x++;
				}
			}

			file.close();
			System.out.println("\nSuccessfully wrote race data to the file");

		} catch (IOException e) {
			System.out.println("\nAn error occurred");
			e.printStackTrace();
		}

	}

	@Override
	public void restoreState() {

		// Read driver data file
		try {
			File file = new File("driver.txt");
			Scanner scn = new Scanner(file);
			int count = 0;
			
			//Save to arrayList
			while (scn.hasNextLine()) {
				String data = scn.nextLine();
				String info[] = data.split("\t", -1); // split from tab spaces

				Formula1Driver dv = new Formula1Driver(info[0], info[1], info[2]);

				dv.setPoints(Integer.parseInt(info[3]));
				dv.setFirstPositions(Integer.parseInt(info[4]));
				dv.setSecondPositions(Integer.parseInt(info[5]));
				dv.setThirdPositions(Integer.parseInt(info[6]));

				drivers.add(dv);
				count++;
			}
			System.out.println("Data loaded from drivers file");
			scn.close();
		} catch (FileNotFoundException e) {
			System.out.println("No saved state");
		}

		// Read race data file
		try {
			File file = new File("race.txt");
			Scanner scn = new Scanner(file);
			int count = 0;
			while (scn.hasNextLine()) {
				String data = scn.nextLine();
				String info[] = data.split("\t", -1); // split from tab spaces

				Map<String, Integer> pos = new HashMap<>();

				// Save race details
				pos.put(info[1], Integer.parseInt(info[2]));

				Races rc = new Races(info[0], pos);
				if (rc.getDate() != "")
					raceData.add(rc); // Add object to arrayList
				count++;
			}
			System.out.println("Data loaded from races file\n");
			scn.close();

		} catch (FileNotFoundException e) {
			System.out.println("No saved state..! \n");
		}

	}
}
